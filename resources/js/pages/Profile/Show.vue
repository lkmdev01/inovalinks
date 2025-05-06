<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DynamicIcon from '@/components/ui/DynamicIcon.vue';
import { ref, computed } from 'vue';

interface Profile {
  id: number;
  title: string;
  description: string | null;
  slug: string;
  avatar: string | null;
  theme: string;
  user?: {
    name: string;
  };
}

interface Link {
  id: number;
  title: string;
  url: string;
  icon: string;
}

const props = defineProps<{
  profile: Profile;
  links: Link[];
}>();

// Função para verificar se o avatar é uma URL absoluta ou um caminho relativo
const getAvatarUrl = computed(() => {
  if (!props.profile.avatar) return null;
  
  // Se já for uma URL completa, retorna como está
  if (props.profile.avatar.startsWith('http')) {
    return props.profile.avatar;
  }
  
  // Caso contrário, usa o caminho relativo
  return props.profile.avatar;
});

const themeClasses = computed(() => {
  switch (props.profile.theme) {
    case 'dark':
      return {
        bg: 'bg-gray-900',
        text: 'text-white',
        card: 'bg-gray-800 hover:bg-gray-700 border-gray-700',
      };
    case 'blue':
      return {
        bg: 'bg-blue-100',
        text: 'text-blue-900',
        card: 'bg-white hover:bg-blue-50 border-blue-200',
      };
    case 'green':
      return {
        bg: 'bg-green-100',
        text: 'text-green-900',
        card: 'bg-white hover:bg-green-50 border-green-200',
      };
    case 'purple':
      return {
        bg: 'bg-purple-100',
        text: 'text-purple-900',
        card: 'bg-white hover:bg-purple-50 border-purple-200',
      };
    case 'pink':
      return {
        bg: 'bg-pink-100',
        text: 'text-pink-900',
        card: 'bg-white hover:bg-pink-50 border-pink-200',
      };
    default:
      return {
        bg: 'bg-white',
        text: 'text-gray-900',
        card: 'bg-white hover:bg-gray-50 border-gray-200',
      };
  }
});
</script>

<template>
  <Head :title="profile.title || (profile.user?.name ? `${profile.user.name}'s Links` : 'Links')" />

  <div
    class="flex min-h-screen flex-col items-center"
    :class="[themeClasses.bg, themeClasses.text]"
  >
    <div class="flex w-full max-w-md flex-col items-center px-4 py-12">
      <div class="mb-8 flex flex-col items-center text-center">
        <div
          v-if="getAvatarUrl"
          class="mb-4 h-24 w-24 overflow-hidden rounded-full border-2 border-primary"
        >
          <img :src="getAvatarUrl" :alt="profile.title" class="h-full w-full object-cover" />
        </div>
        <div v-else class="mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-primary/10 text-primary">
          <span class="text-2xl font-bold">{{ profile.user?.name?.charAt(0) || '?' }}</span>
        </div>
        <h1 class="mb-1 text-2xl font-bold">{{ profile.title || (profile.user?.name ? `${profile.user.name}'s Links` : 'Links') }}</h1>
        <p v-if="profile.description" class="text-sm opacity-80">{{ profile.description }}</p>
      </div>

      <div class="w-full space-y-3">
        <a
          v-for="link in links"
          :key="link.id"
          :href="link.url"
          target="_blank"
          rel="noopener noreferrer"
          class="flex w-full items-center justify-center gap-2 rounded-md border p-3 text-center font-medium transition-all"
          :class="themeClasses.card"
        >
          <DynamicIcon :name="link.icon || 'link'" class="h-5 w-5" />
          <span>{{ link.title }}</span>
        </a>
      </div>

      <div class="mt-12 text-sm opacity-70">
        <p>Powered by Laravue LinkTree</p>
      </div>
    </div>
  </div>
</template> 