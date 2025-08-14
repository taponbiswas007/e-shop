<script setup>
import { ref, onMounted } from 'vue';
import { Chart as ChartJS } from 'chart.js';

const props = defineProps({
    data: Object,
    type: {
        type: String,
        default: 'line'
    }
});

const chartRef = ref(null);
const chartInstance = ref(null);

onMounted(() => {
    if (chartRef.value) {
        chartInstance.value = new ChartJS(chartRef.value, {
            type: props.type,
            data: props.data,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
</script>

<template>
    <canvas ref="chartRef"></canvas>
</template>
