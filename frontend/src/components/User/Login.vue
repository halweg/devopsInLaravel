<template>
  <div>
    <form>
      <label for="email">Email</label>
      <input type="email" v-model="email" id="email">

      <label for="password">Password</label>
      <input type="password" v-model="password" id="password">

      <button @click.prevent="login">Login</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',

  data() {
    return {
      email: '',
      password: '',
    };
  },

  methods: {
    async login() {
        const { data } = await axios.post(`/api/auth/login`, { email: this.email, password: this.password });

        localStorage.setItem('access_token', data.data.token);

        await this.$router.push({ name: 'posts-list' });
    }
  }
}
</script>
