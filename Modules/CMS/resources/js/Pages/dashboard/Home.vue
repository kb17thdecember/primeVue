<template>
  <Breadcrumb :items="[{ label: 'Dashboard' }]" />

  <div class="flex mt-6 justify-between">
    <div class="w-[24%]" v-for="(card, index) in cards" :key="index">
      <div class="card mb-0">
        <div class="flex justify-between mb-4">
          <div>
            <span class="block text-muted-color font-medium mb-4">{{ card.title }}</span>
            <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">{{ card.value }}</div>
          </div>
          <div
            class="flex items-center justify-center rounded-border"
            :class="card.iconBg"
            style="width: 2.5rem; height: 2.5rem"
          >
            <i :class="card.icon + ' !text-xl ' + card.iconColor"></i>
          </div>
        </div>
        <span class="text-primary font-medium">{{ card.meta1 }}</span>
        <span class="text-muted-color">{{ card.meta2 }}</span>
      </div>
    </div>
  </div>

  <div class="card mt-8">
    <h3 class="text-xl font-bold mb-4">Shop Access Frequency (30 days)</h3>
    <Chart type="line" :data="chartData" :options="chartOptions" class="w-full" />
  </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import {usePage} from '@inertiajs/vue3'
import Breadcrumb from '../../component/Breadcrumb.vue'
import Chart from 'primevue/chart'

const {props} = usePage()

const cards = ref([
  {
    title: 'Orders',
    value: '152',
    icon: 'pi pi-shopping-cart',
    iconColor: 'text-blue-500',
    iconBg: 'bg-blue-100 dark:bg-blue-400/10',
    meta1: '24 new ',
    meta2: 'since last visit',
  },
  {
    title: 'Revenue',
    value: '$2.100',
    icon: 'pi pi-dollar',
    iconColor: 'text-orange-500',
    iconBg: 'bg-orange-100 dark:bg-orange-400/10',
    meta1: '%52+ ',
    meta2: 'since last week',
  },
  {
    title: 'Customers',
    value: '28441',
    icon: 'pi pi-users',
    iconColor: 'text-cyan-500',
    iconBg: 'bg-cyan-100 dark:bg-cyan-400/10',
    meta1: '520 ',
    meta2: 'newly registered',
  },
  {
    title: 'Comments',
    value: '152 Unread',
    icon: 'pi pi-comment',
    iconColor: 'text-purple-500',
    iconBg: 'bg-purple-100 dark:bg-purple-400/10',
    meta1: '85 ',
    meta2: 'responded',
  },
])

const chartData = ref({
  labels: [],
  datasets: [
    {
      label: 'Daily Access',
      data: [],
      borderColor: '#42A5F5',
      fill: false,
      tension: 0.4,
    },
  ],
})

const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      labels: {
        color: '#495057',
      },
    },
  },
  scales: {
    x: {
      ticks: {color: '#495057'},
      grid: {color: '#ebedef'},
    },
    y: {
      ticks: {color: '#495057'},
      grid: {color: '#ebedef'},
    },
  },
})

onMounted(() => {
  if (Array.isArray(props.frequency)) {
    chartData.value.labels = props.frequency.map(item => item.date)
    chartData.value.datasets[0].data = props.frequency.map(item => item.daily_count)
  } else if (props.frequency?.date) {
    chartData.value.labels = [props.frequency.date]
    chartData.value.datasets[0].data = [props.frequency.daily_count]
  }
})
</script>
