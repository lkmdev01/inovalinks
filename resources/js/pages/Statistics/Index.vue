<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { ref, computed, onMounted } from 'vue';
import { ChartLine, Smartphone, Link as LinkIcon, Users, Calendar } from 'lucide-vue-next';
import { Bar as BarChart, Pie as PieChart } from 'vue-chartjs';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

interface LinkStats {
  id: number;
  title: string;
  url: string;
  visits: number;
}

interface DeviceStats {
  device: string;
  count: number;
}

interface DailyStats {
  date: string;
  count: number;
}

interface VisitDetails {
  id: number;
  link_title: string;
  link_url: string;
  date: string;
  device: string;
  country: string;
  city: string;
}

const props = defineProps<{
  totalVisits: number;
  recentVisits: VisitDetails[];
  topLinks: LinkStats[];
  deviceStats: DeviceStats[];
  dailyStats: DailyStats[];
  period: string;
  hasData: boolean;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Estatísticas',
    href: '/statistics',
  },
];

// Dados para o gráfico de visitas diárias
const dailyChartData = computed(() => {
  return {
    labels: props.dailyStats.map(stat => stat.date),
    datasets: [
      {
        label: 'Visitas',
        data: props.dailyStats.map(stat => stat.count),
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        tension: 0.3,
      },
    ],
  };
});

// Dados para o gráfico de dispositivos
const deviceChartData = computed(() => {
  return {
    labels: props.deviceStats.map(stat => stat.device),
    datasets: [
      {
        label: 'Dispositivos',
        data: props.deviceStats.map(stat => stat.count),
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)',
          'rgba(54, 162, 235, 0.5)',
          'rgba(255, 206, 86, 0.5)',
          'rgba(75, 192, 192, 0.5)',
          'rgba(153, 102, 255, 0.5)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
        ],
        borderWidth: 1,
      },
    ],
  };
});

// Opções do gráfico
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        precision: 0,
      },
    },
  },
};

const pieChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
};

// Formatação de números
function formatNumber(num: number): string {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M';
  }
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'k';
  }
  return num.toString();
}

// Formatação de tipos de dispositivo
function formatDeviceType(type: string): string {
  if (!type) return 'Desconhecido';
  
  const types: { [key: string]: string } = {
    'desktop': 'Desktop',
    'mobile': 'Celular',
    'tablet': 'Tablet',
  };
  
  return types[type] || type;
}
</script>

<template>
  <Head title="Estatísticas" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Estatísticas</h1>
      </div>
      
      <div v-if="!hasData" class="my-12 flex flex-col items-center justify-center space-y-4 text-center">
        <div class="rounded-full bg-primary/10 p-4">
          <ChartLine class="h-10 w-10 text-primary" />
        </div>
        <h2 class="text-xl font-semibold">Nenhuma estatística disponível</h2>
        <p class="text-muted-foreground">
          Nós ainda não registramos visitas aos seus links. Compartilhe sua página para começar a ver estatísticas.
        </p>
      </div>
      
      <div v-else>
        <!-- Cards de resumo -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
          <!-- Total de visitas -->
          <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle class="text-sm font-medium">Total de Visitas</CardTitle>
              <Users class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold">{{ formatNumber(totalVisits) }}</div>
              <p class="text-xs text-muted-foreground">Desde o início</p>
            </CardContent>
          </Card>
          
          <!-- Links ativos -->
          <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle class="text-sm font-medium">Links Ativos</CardTitle>
              <LinkIcon class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold">{{ topLinks.length }}</div>
              <p class="text-xs text-muted-foreground">Com estatísticas</p>
            </CardContent>
          </Card>
          
          <!-- Dispositivos mais usados -->
          <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle class="text-sm font-medium">Principal Dispositivo</CardTitle>
              <Smartphone class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold capitalize">
                {{ deviceStats.length > 0 ? formatDeviceType(deviceStats[0].device) : 'N/A' }}
              </div>
              <p class="text-xs text-muted-foreground">Mais utilizado</p>
            </CardContent>
          </Card>
          
          <!-- Período ativo -->
          <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle class="text-sm font-medium">Período</CardTitle>
              <Calendar class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold">
                {{ period === '7days' ? '7 dias' : period === '30days' ? '30 dias' : period === '90days' ? '90 dias' : 'Todos' }}
              </div>
              <p class="text-xs text-muted-foreground">Filtro atual</p>
            </CardContent>
          </Card>
        </div>
        
        <!-- Tabs de estatísticas -->
        <Tabs default-value="overview" class="w-full">
          <TabsList>
            <TabsTrigger value="overview">Visão Geral</TabsTrigger>
            <TabsTrigger value="visits">Visitas Recentes</TabsTrigger>
            <TabsTrigger value="links">Links Populares</TabsTrigger>
          </TabsList>
          
          <!-- Tab: Visão Geral -->
          <TabsContent value="overview">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <!-- Gráfico de visitas diárias -->
              <Card>
                <CardHeader>
                  <CardTitle>Visitas ao Longo do Tempo</CardTitle>
                  <CardDescription>
                    Total de visitas diárias nos últimos {{ dailyStats.length }} dias
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div class="h-80">
                    <BarChart 
                      :data="dailyChartData" 
                      :options="chartOptions"
                    />
                  </div>
                </CardContent>
              </Card>
              
              <!-- Gráfico de dispositivos -->
              <Card>
                <CardHeader>
                  <CardTitle>Dispositivos</CardTitle>
                  <CardDescription>
                    Distribuição de visitas por tipo de dispositivo
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div class="h-80">
                    <PieChart 
                      :data="deviceChartData"
                      :options="pieChartOptions"
                    />
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>
          
          <!-- Tab: Visitas Recentes -->
          <TabsContent value="visits">
            <Card>
              <CardHeader>
                <CardTitle>Visitas Recentes</CardTitle>
                <CardDescription>
                  As últimas 10 visitas aos seus links
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div class="overflow-x-auto">
                  <table class="w-full border-collapse">
                    <thead>
                      <tr class="border-b text-left text-sm font-medium text-muted-foreground">
                        <th class="pb-2 pt-3.5">Link</th>
                        <th class="pb-2 pt-3.5">Data</th>
                        <th class="pb-2 pt-3.5">Dispositivo</th>
                        <th class="pb-2 pt-3.5">Localização</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr 
                        v-for="visit in recentVisits" 
                        :key="visit.id"
                        class="border-b text-sm last:border-0 hover:bg-muted/50"
                      >
                        <td class="py-3 pr-4">
                          <div class="font-medium">{{ visit.link_title }}</div>
                          <div class="mt-1 text-xs text-muted-foreground">
                            {{ visit.link_url.length > 40 ? visit.link_url.substring(0, 40) + '...' : visit.link_url }}
                          </div>
                        </td>
                        <td class="py-3 pr-4">{{ visit.date }}</td>
                        <td class="py-3 pr-4 capitalize">{{ formatDeviceType(visit.device) }}</td>
                        <td class="py-3 pr-4">
                          {{ visit.country ? (visit.city ? `${visit.city}, ${visit.country}` : visit.country) : 'Desconhecido' }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
          
          <!-- Tab: Links Populares -->
          <TabsContent value="links">
            <Card>
              <CardHeader>
                <CardTitle>Links Populares</CardTitle>
                <CardDescription>
                  Seus links com mais visitas
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div class="space-y-4">
                  <div 
                    v-for="link in topLinks" 
                    :key="link.id"
                    class="flex items-center justify-between rounded-md border p-4 shadow-sm transition-colors hover:bg-accent"
                  >
                    <div class="flex-1 mr-4">
                      <div class="font-medium">{{ link.title }}</div>
                      <div class="mt-1 text-xs text-muted-foreground truncate max-w-md">
                        {{ link.url }}
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="text-xl font-bold">{{ formatNumber(link.visits) }}</div>
                      <Link 
                        :href="route('statistics.link', { link: link.id })" 
                        class="text-xs text-primary hover:underline"
                      >
                        Ver detalhes
                      </Link>
                    </div>
                  </div>
                  
                  <div v-if="topLinks.length === 0" class="text-center py-8 text-muted-foreground">
                    Nenhum link tem visitas no período selecionado
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  </AppLayout>
</template> 