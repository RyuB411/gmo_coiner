<template>
  <div class="container">
    <div v-for="news in news" :key="news.title" class="py-1 mx-1">
      <a :href="news.url" target="_blank">
        <div class="row border py-1 mx-1">
          <div  class="col-md-1 col-sm-1">
            <img :src="news.img" width="48" height="48">
          </div>
          <div class="h5 col-md-8 col-sm-8" v-text="news.title"/>
          <div class="small text-muted col-md-3 col-sm-3" v-text="news.site_name + ' - ' + news.time"/>
        </div>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name:'news-component',
  data() {
    return {
      news: [],
    };
  },
  mounted() {
    this.getNews();
  },
  methods: {
    async getNews() {
      const ret = await window.axios.get('/api/news/');
      this.news = ret.data;
    },
  }
};
</script>
