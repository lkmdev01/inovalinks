<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { getIcon, type IconName } from '@/components/ui/dynamic-icon';
import DynamicIcon from '@/components/ui/DynamicIcon.vue';
import { Switch } from '@/components/ui/switch';
import { computed, ref } from 'vue';

interface LinkItem {
  id: number;
  title: string;
  url: string;
  icon: string;
  is_active: boolean;
}

const props = defineProps<{
  link: LinkItem;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Links',
    href: '/links',
  },
  {
    title: 'Editar Link',
    href: `/links/${props.link.id}/edit`,
  },
];

const form = useForm({
  title: props.link.title,
  url: props.link.url,
  icon: props.link.icon || 'link',
  is_active: props.link.is_active,
});

// Lista de ícones disponíveis agrupados por categoria
type IconCategoryType = {
  [key: string]: string[];
};

const iconCategories = computed<IconCategoryType>(() => {
  return {
    'Redes Sociais': [
      'instagram', 'facebook', 'twitter', 'x', 'linkedin', 'github', 'youtube', 
      'tiktok', 'pinterest', 'twitch', 'discord', 'slack', 'dribbble', 'behance', 'figma'
    ],
    'Comunicação': [
      'mail', 'phone', 'whatsapp', 'telegram', 'chat'
    ],
    'Web': [
      'link', 'website', 'globe', 'code', 'qrcode'
    ],
    'Mídia': [
      'music', 'spotify', 'video', 'camera', 'podcast'
    ],
    'Compras': [
      'store', 'shopping', 'wallet', 'payment'
    ],
    'Profissional': [
      'portfolio', 'education', 'document', 'bookmark', 'work'
    ],
    'Outros': [
      'heart', 'star', 'coffee', 'restaurant', 'map', 'award', 'share'
    ]
  };
});

// Lista completa de ícones para compatibilidade com o código existente
const availableIcons = computed(() => {
  return Object.values(iconCategories.value).flat();
});

// Categoria selecionada
const selectedCategory = ref('Redes Sociais');

// Inicializa a categoria selecionada baseada no ícone atual
const initSelectedCategory = () => {
  for (const [category, icons] of Object.entries(iconCategories.value)) {
    if (icons.includes(form.icon)) {
      selectedCategory.value = category;
      break;
    }
  }
};
// Chama a função para inicializar a categoria
initSelectedCategory();

function submit() {
  form.put(`/links/${props.link.id}`, {
    onSuccess: () => {
      // Redirecionar usando caminho relativo, sem protocolo ou domínio
      window.location.href = '/links';
    }
  });
}
</script>

<template>
  <Head title="Editar Link" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-4">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Editar Link</h1>
        <Link
          href="/links"
          class="rounded-md bg-secondary px-4 py-2 text-secondary-foreground hover:bg-secondary/80"
        >
          Voltar
        </Link>
      </div>

      <div class="rounded-lg border bg-card p-6">
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label for="title" class="mb-2 block text-sm font-medium">Título</label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
              placeholder="Meu site pessoal"
              required
            />
            <div v-if="form.errors.title" class="mt-1 text-sm text-red-500">
              {{ form.errors.title }}
            </div>
          </div>

          <div>
            <label for="url" class="mb-2 block text-sm font-medium">URL</label>
            <input
              id="url"
              v-model="form.url"
              type="url"
              class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
              placeholder="https://exemplo.com"
              required
            />
            <div v-if="form.errors.url" class="mt-1 text-sm text-red-500">
              {{ form.errors.url }}
            </div>
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium">Ícone</label>
            
            <div class="mb-4 flex flex-wrap gap-2">
              <button
                v-for="category in Object.keys(iconCategories)"
                :key="category"
                type="button"
                class="rounded-md px-3 py-1 text-sm transition-colors"
                :class="selectedCategory === category ? 'bg-primary text-primary-foreground' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                @click="selectedCategory = category"
              >
                {{ category }}
              </button>
            </div>
            
            <div class="grid grid-cols-4 gap-2 md:grid-cols-8 lg:grid-cols-10">
              <button
                v-for="icon in iconCategories[selectedCategory]"
                :key="icon"
                type="button"
                class="flex aspect-square items-center justify-center rounded-md border p-2 transition-colors"
                :class="form.icon === icon ? 'border-primary bg-primary/10' : 'border-input hover:bg-accent'"
                @click="form.icon = icon"
              >
                <DynamicIcon :name="icon" />
              </button>
            </div>
            
            <div v-if="form.errors.icon" class="mt-1 text-sm text-red-500">
              {{ form.errors.icon }}
            </div>
          </div>

          <div class="flex items-center space-x-2">
            <Switch 
              id="is_active" 
              :checked="form.is_active" 
              @update:checked="(value) => form.is_active = value" 
            />
            <label
              for="is_active"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              Ativo
            </label>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Salvando...' : 'Atualizar Link' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>