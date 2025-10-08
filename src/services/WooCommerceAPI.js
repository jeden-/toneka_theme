import AsyncStorage from '@react-native-async-storage/async-storage';

class WooCommerceAPI {
  constructor() {
    // TODO: Zmień na prawdziwe dane z Twojego WooCommerce
    this.baseURL = 'https://toneka.pl/wp-json/wc/v3';
    this.consumerKey = 'your_consumer_key';
    this.consumerSecret = 'your_consumer_secret';
    this.authTokenKey = 'toneka_auth_token';
  }

  // Helper method to get auth token
  async getAuthToken() {
    try {
      return await AsyncStorage.getItem(this.authTokenKey);
    } catch (error) {
      console.error('Error getting auth token:', error);
      return null;
    }
  }

  // Helper method to make authenticated requests
  async makeRequest(endpoint, options = {}) {
    const token = await this.getAuthToken();
    
    const defaultOptions = {
      headers: {
        'Content-Type': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
      },
    };

    const finalOptions = {
      ...defaultOptions,
      ...options,
      headers: {
        ...defaultOptions.headers,
        ...options.headers,
      },
    };

    try {
      const response = await fetch(`${this.baseURL}${endpoint}`, finalOptions);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      return await response.json();
    } catch (error) {
      console.error('API request failed:', error);
      throw error;
    }
  }

  // Authentication
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
        await AsyncStorage.setItem(this.authTokenKey, data.token);
        await AsyncStorage.setItem('toneka_user_data', JSON.stringify(data.user));
        return { success: true, user: data.user, token: data.token };
      } else {
        throw new Error(data.message || 'Login failed');
      }
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  }

  async logout() {
    try {
      await AsyncStorage.removeItem(this.authTokenKey);
      await AsyncStorage.removeItem('toneka_user_data');
      return { success: true };
    } catch (error) {
      console.error('Logout error:', error);
      throw error;
    }
  }

  async getCurrentUser() {
    try {
      const userData = await AsyncStorage.getItem('toneka_user_data');
      return userData ? JSON.parse(userData) : null;
    } catch (error) {
      console.error('Get user error:', error);
      return null;
    }
  }

  // Products
  async getProducts(params = {}) {
    const queryParams = new URLSearchParams(params).toString();
    const endpoint = `/products${queryParams ? `?${queryParams}` : ''}`;
    return await this.makeRequest(endpoint);
  }

  async getProduct(productId) {
    return await this.makeRequest(`/products/${productId}`);
  }

  async getProductCategories() {
    return await this.makeRequest('/products/categories');
  }

  // Sample tracks for shop
  async getSampleTracks(categoryId = null) {
    try {
      let params = { category: 'samples' };
      if (categoryId) {
        params.category = categoryId;
      }
      
      const products = await this.getProducts(params);
      
      return products.map(product => ({
        id: product.id,
        title: product.name,
        artist: this.extractArtist(product.name),
        cover: product.images[0]?.src || null,
        sampleUrl: this.getMetaValue(product.meta_data, '_sample_audio'),
        price: product.price,
        category: product.categories[0]?.name || 'Słuchowiska',
        duration: parseInt(this.getMetaValue(product.meta_data, '_duration_minutes')) * 60 || 0,
        description: product.short_description,
        fullDescription: product.description,
      }));
    } catch (error) {
      console.error('Error fetching sample tracks:', error);
      throw error;
    }
  }

  // Purchased tracks
  async getPurchasedTracks(userId) {
    try {
      const orders = await this.makeRequest(`/orders?customer=${userId}&status=completed`);
      
      const tracks = [];
      for (const order of orders) {
        for (const item of order.line_items) {
          const product = await this.getProduct(item.product_id);
          
          tracks.push({
            id: item.product_id,
            title: item.name,
            artist: this.extractArtist(item.name),
            cover: product.images[0]?.src || null,
            audioUrl: this.getMetaValue(product.meta_data, '_audio_file'),
            duration: parseInt(this.getMetaValue(product.meta_data, '_duration_minutes')) * 60 || 0,
            purchaseDate: order.date_created,
            orderId: order.id,
            isPurchased: true,
          });
        }
      }
      
      return tracks;
    } catch (error) {
      console.error('Error fetching purchased tracks:', error);
      throw error;
    }
  }

  // Orders
  async createOrder(orderData) {
    return await this.makeRequest('/orders', {
      method: 'POST',
      body: JSON.stringify(orderData),
    });
  }

  async purchaseTrack(productId, paymentData) {
    try {
      const orderData = {
        payment_method: 'stripe',
        line_items: [{
          product_id: productId,
          quantity: 1
        }],
        billing: paymentData.billing,
        shipping: paymentData.shipping,
        status: 'processing',
      };

      const order = await this.createOrder(orderData);
      
      // Po udanym zakupie, pobierz URL do pełnego utworu
      if (order.status === 'processing' || order.status === 'completed') {
        const product = await this.getProduct(productId);
        const audioUrl = this.getMetaValue(product.meta_data, '_audio_file');
        return { ...order, audioUrl };
      }
      
      return order;
    } catch (error) {
      console.error('Error purchasing track:', error);
      throw error;
    }
  }

  // Helper methods
  extractArtist(productName) {
    // Logika do wyciągnięcia artysty z nazwy produktu
    // Można to dostosować do Twojej struktury nazw
    const parts = productName.split(' - ');
    return parts.length > 1 ? parts[1] : 'Nieznany artysta';
  }

  getMetaValue(metaData, key) {
    const meta = metaData.find(item => item.key === key);
    return meta ? meta.value : null;
  }

  // Search
  async searchProducts(query, category = null) {
    const params = { search: query };
    if (category) {
      params.category = category;
    }
    return await this.getProducts(params);
  }

  // Featured products for homepage
  async getFeaturedProducts(limit = 6) {
    const products = await this.getProducts({ featured: true, per_page: limit });
    return products.map(product => ({
      id: product.id,
      title: product.name,
      artist: this.extractArtist(product.name),
      cover: product.images[0]?.src || null,
      price: product.price,
      category: product.categories[0]?.name || 'Słuchowiska',
      duration: parseInt(this.getMetaValue(product.meta_data, '_duration_minutes')) * 60 || 0,
      sampleUrl: this.getMetaValue(product.meta_data, '_sample_audio'),
    }));
  }

  // Latest products
  async getLatestProducts(limit = 6) {
    const products = await this.getProducts({ orderby: 'date', order: 'desc', per_page: limit });
    return products.map(product => ({
      id: product.id,
      title: product.name,
      artist: this.extractArtist(product.name),
      cover: product.images[0]?.src || null,
      price: product.price,
      category: product.categories[0]?.name || 'Słuchowiska',
      duration: parseInt(this.getMetaValue(product.meta_data, '_duration_minutes')) * 60 || 0,
      sampleUrl: this.getMetaValue(product.meta_data, '_sample_audio'),
    }));
  }
}

export default new WooCommerceAPI();
