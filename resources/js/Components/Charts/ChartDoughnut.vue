<script setup>
import Chart from './Chart.vue';

defineProps({
    data: Object
});
</script>

<template>
    <Chart :data="data" type="doughnut" />
</template>
