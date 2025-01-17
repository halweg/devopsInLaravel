<template>
<div>
    <router-link :to="{ name: 'posts-form' }">New post</router-link>
    <table>
        <tr>
            <th>Title</th>
            <th>Headline</th>
            <th>Publish at</th>
            <th></th>
        </tr>
        <tr v-for="post in posts" v-bind:key="post.id">
            <td>{{ post.title }}</td>
            <td>{{ post.headline }}</td>
            <td>{{ post.publish_at }}</td>
            <td>
                <button v-if="!post.is_published" @click="publish(post)">Publish</button>
            </td>
        </tr>
    </table>
</div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PostList',

    data() {
        return {
            posts: [],
        };
    },

    async created() {
        await this.fetch();
    },

    methods: {
        async fetch() {
          const { data } = await axios.get('/api/posts', {
            headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` },
          });

          this.posts = data.data;
        },

        async publish(post) {
          await axios.patch(`/api/posts/${post.id}/publish`, {}, {
            headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` },
          });

          await this.fetch();
        }
    }
}
</script>
