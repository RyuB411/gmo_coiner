// https://github.com/ehajri/vue-chartjs-financial/blob/master/src/components/Candlestick.js
import { Candlestick, mixins } from 'vue-chartjs-financial';
const { reactiveProp } = mixins;

export default {
  extends: Candlestick,
  mixins: [reactiveProp],
  props: ['options'],
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
};