<!-- https://github.com/ehajri/vue-chartjs-financial/blob/master/src/views/Candlestick.vue -->
<template>
  <div class="container border">
    <Candlestick :chart-data="datacollection" :options="options"/>
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
            data: ret.data['ohlc'],
          },
          {
            type: 'line',
            label: 'sma:7',
            data: ret.data['sma']['7'],
            borderColor : "rgba(100,200,250,0.8)",
            pointStyle: 'line',
            fill: false,
            tension: 0,
          },
          {
            type: 'line',
            label: 'sma:20',
            data: ret.data['sma']['20'],
            borderColor : "rgba(50,150,250,0.8)",
            pointStyle: 'line',
            fill: false,
            tension: 0,
          },
          {
            type: 'line',
            label: 'sma:60',
            data: ret.data['sma']['60'],
            borderColor : "rgba(25,100,250,0.8)",
            pointStyle: 'line',
            fill: false,
            tension: 0,
          },
          {
            type: 'bar',
            label: 'volume',
            data: ret.data['volume'],
            borderColor : "rgba(100,100,100,0.5)",
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
