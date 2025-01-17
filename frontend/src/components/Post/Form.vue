<template>
  <div>
    <form>
      <label for="title">Title</label>
      <input type="text" v-model="post.title" id="title">

      <label for="headline">Headline</label>
      <input type="text" v-model="post.headline" id="headline">

      <label for="content">Content</label>
      <textarea v-model="post.content" id="content"></textarea>

      <button @click.prevent="submit">Submit</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PostForm',

  data() {
    return {
      post: {},
    };
  },

  methods: {
    async submit() {
      await axios.post(`/api/posts/`, this.post, {
        headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` },
      });

      await this.$router.push({ name: 'posts-list' })
    }
  }
}
</script>
