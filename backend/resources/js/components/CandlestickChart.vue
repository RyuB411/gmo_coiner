<!-- https://github.com/ehajri/vue-chartjs-financial/blob/master/src/views/Candlestick.vue -->
<template>
  <div class="container border">
    <Candlestick :chart-data="datacollection" :options="options" />
  </div>
</template>

<script>
import Candlestick from   '../chart/Candlestick.js';
export default {
  components: {
    Candlestick,
  },
  data() {
    return {
      datacollection: {},
      options: {},
    };
  },
  mounted() {
    this.fillData();
    setInterval(() => {
      this.fillData();
    }, 10000);
  },
  methods: {
    async fillData() {
      const ret = await window.axios.get('/api/candle_stick/' +  this.cryptSymbol);
      this.datacollection = {
        datasets: [
          {
            type: 'candlestick',
            label: 'OHLC',
            data: ret.data,
          },
        ],
      };
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
