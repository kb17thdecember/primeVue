<template>
  <Breadcrumb :items="[{ label: 'Dashboard' }]" />

  <div class="flex mt-6 justify-between">
    <div class="w-[24%]" v-for="(card, index) in cards" :key="index">
      <div class="card mb-0" style="min-height: 150px">
        <div class="flex justify-between mb-4">
          <div>
            <span class="block text-muted-color font-medium mb-4">{{ card.title }}</span>
            <div class="font-medium text-xl">{{ card.value }}</div>
          </div>
          <div
            class="flex items-center justify-center rounded-border"
            :class="card.iconBg"
            style="width: 2.5rem; height: 2.5rem"
          >
            <i :class="card.icon + ' !text-xl ' + card.iconColor"></i>
          </div>
        </div>
        <p>
          <span class="text-primary font-medium" v-if="card.meta1">{{ card.meta1 + ' ' }}</span>
          <span class="text-muted-color" v-if="card.meta2">({{ card.meta2 }})</span>
        </p>
      </div>
    </div>
  </div>

  <div class="flex gap-6 mt-8">
    <div class="w-1/3">
      <div class="card !mb-1">
        <h3 class="text-lg font-bold mb-4">Daily Access</h3>
        <Chart type="bar" :data="dayChartData" :options="chartOptions" class="w-full h-[300px]" />
      </div>
    </div>

    <div class="w-1/3">
      <div class="card !mb-1">
        <h3 class="text-lg font-bold mb-4">Monthly Access</h3>
        <Chart type="bar" :data="monthChartData" :options="chartOptions" class="w-full h-[300px]" />
      </div>
    </div>

    <div class="w-1/3">
      <div class="card !mb-1">
        <h3 class="text-lg font-bold mb-4">Yearly Access</h3>
        <Chart type="bar" :data="yearChartData" :options="chartOptions" class="w-full h-[300px]" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Breadcrumb from '../../component/Breadcrumb.vue'
import Chart from 'primevue/chart'

const { props } = usePage()

const cards = ref([
  {
    title: 'Orders',
    value: props.dataReport.order_qty,
    icon: 'pi pi-shopping-cart',
    iconColor: 'text-blue-500',
    iconBg: 'bg-blue-100 dark:bg-blue-400/10',
    meta1: props.dataReport.order_qty_current_month + ' new',
    meta2: 'current month',
  },
  {
    title: 'Month revenue',
    value: '$' + props.dataReport.revenue,
    icon: 'pi pi-dollar',
    iconColor: 'text-orange-500',
    iconBg: 'bg-orange-100 dark:bg-orange-400/10',
  },
  {
    title: 'Total Revenue',
    value: '$' + props.dataReport.revenue_current_month,
    icon: 'pi pi-dollar',
    iconColor: 'text-orange-500',
    iconBg: 'bg-orange-100 dark:bg-orange-400/10',
  },
  {
    title: 'API token',
    value: props.dataReport.token_available,
    icon: 'pi pi-users',
    iconColor: 'text-cyan-500',
    iconBg: 'bg-cyan-100 dark:bg-cyan-400/10',
    meta1: props.dataReport.token_used,
    meta2: 'used',
  },
])

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
      ticks: { color: '#495057' },
      grid: { color: '#ebedef' },
    },
    y: {
      ticks: { color: '#495057' },
      grid: { color: '#ebedef' },
    },
  },
})

const dayChartData = ref({})
const monthChartData = ref({})
const yearChartData = ref({})

onMounted(() => {
  const frequency = props.frequency || []
  if (!Array.isArray(frequency)) return

  // === Daily ===
  const dailyLabels = frequency.map(f => f.date)
  const dailyCounts = frequency.map(f => f.daily_count)
  dayChartData.value = {
    labels: dailyLabels,
    datasets: [
      {
        label: 'Daily Count',
        backgroundColor: '#42A5F5',
        data: dailyCounts,
      },
      {
        label: 'Cumulative',
        type: 'line',
        borderColor: '#FFA726',
        fill: false,
        tension: 0.4,
        data: dailyCounts.reduce((acc, val, i) => {
          acc.push((acc[i - 1] || 0) + val)
          return acc
        }, []),
      },
    ],
  }

  // === Monthly ===
  const monthly = {}
  frequency.forEach(item => {
    const month = item.date.slice(0, 7)
    monthly[month] = (monthly[month] || 0) + item.daily_count
  })
  const monthLabels = Object.keys(monthly).sort()
  const monthCounts = monthLabels.map(m => monthly[m])
  monthChartData.value = {
    labels: monthLabels,
    datasets: [
      {
        label: 'Monthly Count',
        backgroundColor: '#66BB6A',
        data: monthCounts,
      },
      {
        label: 'Cumulative',
        type: 'line',
        borderColor: '#FF7043',
        fill: false,
        tension: 0.4,
        data: monthCounts.reduce((acc, val, i) => {
          acc.push((acc[i - 1] || 0) + val)
          return acc
        }, []),
      },
    ],
  }

  // === Yearly ===
  const yearly = {}
  frequency.forEach(item => {
    const year = item.date.slice(0, 4)
    yearly[year] = (yearly[year] || 0) + item.daily_count
  })
  const yearLabels = Object.keys(yearly).sort()
  const yearCounts = yearLabels.map(y => yearly[y])
  yearChartData.value = {
    labels: yearLabels,
    datasets: [
      {
        label: 'Yearly Count',
        backgroundColor: '#AB47BC',
        data: yearCounts,
      },
      {
        label: 'Cumulative',
        type: 'line',
        borderColor: '#26C6DA',
        fill: false,
        tension: 0.4,
        data: yearCounts.reduce((acc, val, i) => {
          acc.push((acc[i - 1] || 0) + val)
          return acc
        }, []),
      },
    ],
  }
})
</script>
