<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { getIcon, type IconName } from '@/components/ui/dynamic-icon';
import DynamicIcon from '@/components/ui/DynamicIcon.vue';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { computed, ref } from 'vue';

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
    title: 'Criar Link',
    href: '/links/create',
  },
];

const form = useForm({
  title: '',
  url: '',
  icon: 'link',
  is_active: true,
});

// Lista de ícones disponíveis
const availableIcons = computed(() => {
  return ['github', 'twitter', 'instagram', 'linkedin', 'facebook', 'youtube', 'code', 'link'];
});

function submit() {
  form.post('/links');
}
</script>

<template>
  <Head title="Criar Link" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-4">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Criar Novo Link</h1>
        <Link
          href="/links"
          class="rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent"
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
            <div class="grid grid-cols-4 gap-2 sm:grid-cols-8">
              <button
                v-for="icon in availableIcons"
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
              class="rounded-md border-white border-1 px-4 py-2 text-white hover:bg-zinc-800"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Salvando...' : 'Salvar Link' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template> 