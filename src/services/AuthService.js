import AsyncStorage from '@react-native-async-storage/async-storage';
import jwt_decode from 'jwt-decode';

class AuthService {
  constructor() {
    this.tokenKey = 'toneka_auth_token';
    this.userKey = 'toneka_user_data';
    this.refreshTokenKey = 'toneka_refresh_token';
  }

  // Login
  async login(email, password) {
    try {
      const response = await fetch('https://toneka.pl/wp-json/jwt-auth/v1/token', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username: email,
          password: password,
        }),
      });

      const data = await response.json();
      
      if (data.token) {
        // Store tokens and user data
        await AsyncStorage.setItem(this.tokenKey, data.token);
        await AsyncStorage.setItem(this.userKey, JSON.stringify(data.user));
        
        if (data.refresh_token) {
          await AsyncStorage.setItem(this.refreshTokenKey, data.refresh_token);
        }
        
        return { 
          success: true, 
          user: data.user, 
          token: data.token,
          refreshToken: data.refresh_token 
        };
      } else {
        throw new Error(data.message || 'Login failed');
      }
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  }

  // Register
  async register(userData) {
    try {
      const response = await fetch('https://toneka.pl/wp-json/wp/v2/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username: userData.email,
          email: userData.email,
          password: userData.password,
          first_name: userData.firstName,
          last_name: userData.lastName,
        }),
      });

      const data = await response.json();
      
      if (response.ok) {
        // Auto login after registration
        return await this.login(userData.email, userData.password);
      } else {
        throw new Error(data.message || 'Registration failed');
      }
    } catch (error) {
      console.error('Registration error:', error);
      throw error;
    }
  }

  // Logout
  async logout() {
    try {
      await AsyncStorage.multiRemove([
        this.tokenKey,
        this.userKey,
        this.refreshTokenKey,
      ]);
      return { success: true };
    } catch (error) {
      console.error('Logout error:', error);
      throw error;
    }
  }

  // Get current user
  async getCurrentUser() {
    try {
      const userData = await AsyncStorage.getItem(this.userKey);
      return userData ? JSON.parse(userData) : null;
    } catch (error) {
      console.error('Get user error:', error);
      return null;
    }
  }

  // Get auth token
  async getAuthToken() {
    try {
      return await AsyncStorage.getItem(this.tokenKey);
    } catch (error) {
      console.error('Get token error:', error);
      return null;
    }
  }

  // Get refresh token
  async getRefreshToken() {
    try {
      return await AsyncStorage.getItem(this.refreshTokenKey);
    } catch (error) {
      console.error('Get refresh token error:', error);
      return null;
    }
  }

  // Check if user is authenticated
  async isAuthenticated() {
    try {
      const token = await this.getAuthToken();
      if (!token) return false;
      
      // Check if token is expired
      const decoded = jwt_decode(token);
      const currentTime = Date.now() / 1000;
      
      if (decoded.exp < currentTime) {
        // Token expired, try to refresh
        return await this.refreshToken();
      }
      
      return true;
    } catch (error) {
      console.error('Authentication check error:', error);
      return false;
    }
  }

  // Refresh token
  async refreshToken() {
    try {
      const refreshToken = await this.getRefreshToken();
      if (!refreshToken) return false;

      const response = await fetch('https://toneka.pl/wp-json/jwt-auth/v1/token/refresh', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          refresh_token: refreshToken,
        }),
      });

      const data = await response.json();
      
      if (data.token) {
        await AsyncStorage.setItem(this.tokenKey, data.token);
        if (data.refresh_token) {
          await AsyncStorage.setItem(this.refreshTokenKey, data.refresh_token);
        }
        return true;
      } else {
        // Refresh failed, logout user
        await this.logout();
        return false;
      }
    } catch (error) {
      console.error('Refresh token error:', error);
      await this.logout();
      return false;
    }
  }

  // Update user profile
  async updateProfile(userData) {
    try {
      const token = await this.getAuthToken();
      const currentUser = await this.getCurrentUser();
      
      if (!token || !currentUser) {
        throw new Error('User not authenticated');
      }

      const response = await fetch(`https://toneka.pl/wp-json/wp/v2/users/${currentUser.id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
          first_name: userData.firstName,
          last_name: userData.lastName,
          email: userData.email,
        }),
      });

      const data = await response.json();
      
      if (response.ok) {
        // Update stored user data
        const updatedUser = { ...currentUser, ...data };
        await AsyncStorage.setItem(this.userKey, JSON.stringify(updatedUser));
        return { success: true, user: updatedUser };
      } else {
        throw new Error(data.message || 'Profile update failed');
      }
    } catch (error) {
      console.error('Update profile error:', error);
      throw error;
    }
  }

  // Change password
  async changePassword(currentPassword, newPassword) {
    try {
      const token = await this.getAuthToken();
      const currentUser = await this.getCurrentUser();
      
      if (!token || !currentUser) {
        throw new Error('User not authenticated');
      }

      const response = await fetch('https://toneka.pl/wp-json/jwt-auth/v1/token/validate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
          password: currentPassword,
        }),
      });

      if (response.ok) {
        // Current password is valid, update password
        const updateResponse = await fetch(`https://toneka.pl/wp-json/wp/v2/users/${currentUser.id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
          },
          body: JSON.stringify({
            password: newPassword,
          }),
        });

        if (updateResponse.ok) {
          return { success: true };
        } else {
          throw new Error('Password update failed');
        }
      } else {
        throw new Error('Current password is incorrect');
      }
    } catch (error) {
      console.error('Change password error:', error);
      throw error;
    }
  }

  // Forgot password
  async forgotPassword(email) {
    try {
      const response = await fetch('https://toneka.pl/wp-json/bdpwr/v1/reset-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email: email,
        }),
      });

      const data = await response.json();
      
      if (response.ok) {
        return { success: true, message: data.message };
      } else {
        throw new Error(data.message || 'Password reset failed');
      }
    } catch (error) {
      console.error('Forgot password error:', error);
      throw error;
    }
  }

  // Validate token
  async validateToken() {
    try {
      const token = await this.getAuthToken();
      if (!token) return false;

      const response = await fetch('https://toneka.pl/wp-json/jwt-auth/v1/token/validate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
      });

      return response.ok;
    } catch (error) {
      console.error('Validate token error:', error);
      return false;
    }
  }

  // Get user permissions
  async getUserPermissions() {
    try {
      const token = await this.getAuthToken();
      if (!token) return [];

      const response = await fetch('https://toneka.pl/wp-json/wp/v2/users/me', {
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });

      if (response.ok) {
        const user = await response.json();
        return user.capabilities || [];
      } else {
        return [];
      }
    } catch (error) {
      console.error('Get user permissions error:', error);
      return [];
    }
  }
}

export default new AuthService();
