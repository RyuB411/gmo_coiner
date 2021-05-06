<template>
  <div class="container">
    <div class="row">
      <div v-for="ticker in ticker" :key="ticker.symbol" class="col-md-2 col-sm-4 border">
        <a :href="'/chart/' + ticker.symbol">
          <div class="row d-flex align-items-center">
            <img :src="'/img/' + ticker.symbol + '.jpg'" class="img-circle" width="32" height="32"/>
            <div class="h3" v-text="ticker.symbol" />
          </div>
          <div class="h4 font-weight-bold text-center">{{ ticker.close }}</div>
          <div class="h6 font-weight-bold text-center" :class="ticker.after_direction_text">
            <div>{{ ticker.after_day_value }}</div>
            <div>（{{ ticker.after_day_ratio }}％）</div>
          </div>
          <div class="row">
            <div v-text="ticker.low" class="col-6 font-weight-bold text-danger text-left" />
            <div v-text="ticker.high" class="col-6 font-weight-bold text-success text-right" />
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
          <div v-for="sma in ticker.sma" :key="sma.day">
            <div class="row small">
              <div v-text="sma.day + 'SMA:'" class="col-6 text-right"/>
              <div v-text="sma.price" :class="sma.direction" class="col-6 text-left"/>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name:'ticker-list',
  data() {
    return {
      ticker: [],
    };
  },
  mounted() {
    this.getTicker();
    setInterval(() => {
      this.getTicker();
    }, 10000);
  },
  methods: {
    async getTicker() {
      const ret = await window.axios.get('/api/ticker/ALL');
      this.ticker = ret.data;
    },
  }
};
</script>
