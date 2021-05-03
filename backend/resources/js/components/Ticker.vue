<template>
  <div class="container border">
    <h1 v-text="ticker.symbol" />
    <div class="h1 font-weight-bold text-center">{{ ticker.close }}</div>
    <div class="h2 font-weight-bold text-center" :class="ticker.after_direction_text">{{ ticker.after_day_value }}（{{ ticker.after_day_ratio }}％）</div>
    <div class="row">
      <div v-text="ticker.low" class="col-4 font-weight-bold text-danger text-left" />
      <div class="col-4 font-weight-bold text-center">当日レンジ</div>
      <div v-text="ticker.high" class="col-4 font-weight-bold text-success text-right" />
    </div>
    <div class="row">
      <div class="col">
        <div class="progress">
          <div class="progress-bar"
            role="progressbar" 
            style="background-color:#e9ecef;"
            :style="{width: ticker.ratio_from}"
            :aria-valuenow="ticker.close"
            :aria-valuemin="ticker.low"
            :aria-valuemax="ticker.high" />
          <div class="progress-bar progress-bar-striped"
            role="progressbar" 
            :class="ticker.after_direction_bg"
            :style="{width: ticker.ratio_to}"
            :aria-valuenow="ticker.close"
            :aria-valuemin="ticker.low"
            :aria-valuemax="ticker.high" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name:'ticker-component',
  data() {
    return {
      ticker: null,
    };
  },
  mounted() {
    console.log(this);
    this.getTicker();
    setInterval(() => {
      this.getTicker();
    }, 10000);
  },
  methods: {
    async getTicker() {
      const ret = await window.axios.get('/api/ticker/' + this.cryptSymbol);
      this.ticker = ret.data;
    },
  },
  props: {
    cryptSymbol: {
        type: String,
        default: 'BTC'
    }
  }
};
</script>