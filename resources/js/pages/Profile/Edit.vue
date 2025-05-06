<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from '@/components/ui/textarea';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { computed, ref, watch } from 'vue';
import { Upload, Image } from 'lucide-vue-next';

interface Profile {
  id: number;
  user_id: number;
  title: string;
  description: string | null;
  slug: string;
  avatar: string | null;
  theme: string;
}

const props = defineProps<{
  profile: Profile;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Personalizar',
    href: '/profile/edit',
  },
];

const form = useForm({
  title: props.profile.title || '',
  description: props.profile.description || '',
  slug: props.profile.slug,
  theme: props.profile.theme || 'default',
  avatar: props.profile.avatar || '',
  avatar_file: null as File | null,
});

const fileInputRef = ref<HTMLInputElement | null>(null);
const avatarPreview = ref<string | null>(props.profile.avatar);

const publicUrl = computed(() => `/${props.profile.slug}`);

// Atualiza a prévia quando o arquivo é selecionado
watch(() => form.avatar_file, (newFile) => {
  if (newFile) {
    const reader = new FileReader();
    reader.onload = (e) => {
      avatarPreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(newFile);
  }
});

// Para garantir que a URL do avatar seja exibida corretamente
watch(() => form.avatar, (newUrl) => {
  if (newUrl && !form.avatar_file) {
    avatarPreview.value = newUrl;
  }
});

const themes = [
  { id: 'default', name: 'Padrão', bg: 'bg-white', text: 'text-black' },
  { id: 'dark', name: 'Escuro', bg: 'bg-gray-900', text: 'text-white' },
  { id: 'blue', name: 'Azul', bg: 'bg-blue-100', text: 'text-blue-900' },
  { id: 'green', name: 'Verde', bg: 'bg-green-100', text: 'text-green-900' },
  { id: 'purple', name: 'Roxo', bg: 'bg-purple-100', text: 'text-purple-900' },
  { id: 'pink', name: 'Rosa', bg: 'bg-pink-100', text: 'text-pink-900' },
];

function triggerFileInput() {
  if (fileInputRef.value) {
    fileInputRef.value.click();
  }
}

function handleFileChange(event: Event) {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files.length > 0) {
    form.avatar_file = input.files[0];
  }
}

function submit() {
  form.post('/profile', {
    forceFormData: true,
    onSuccess: () => {
      // Limpa o arquivo após o sucesso para evitar conflitos
      form.avatar_file = null;
    }
  });
}
</script>

<template>
  <Head title="Personalizar Perfil" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-4">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Personalizar Perfil</h1>
        <Link
          :href="publicUrl"
          target="_blank"
          class="rounded-md border-white border-1 px-4 py-2 text-white hover:bg-zinc-800"
        >
          Ver Página Pública
        </Link>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
        <div class="col-span-1 lg:col-span-5">
          <Tabs default-value="profile" class="w-full">
            <TabsList class="grid w-full grid-cols-1">
              <TabsTrigger value="profile">Perfil</TabsTrigger>
            </TabsList>
            <TabsContent value="profile">
              <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-4">
                  <label for="title" class="mb-2 block text-sm font-medium">Título</label>
                  <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    placeholder="Meus Links"
                  />
                  <div v-if="form.errors.title" class="mt-1 text-sm text-red-500">
                    {{ form.errors.title }}
                  </div>
                </div>

                <div class="space-y-4">
                  <label for="description" class="mb-2 block text-sm font-medium">Descrição</label>
                  <Textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="resize-none"
                    placeholder="Um pouco sobre mim ou meus links..."
                  />
                  <div v-if="form.errors.description" class="mt-1 text-sm text-red-500">
                    {{ form.errors.description }}
                  </div>
                </div>

                <div class="space-y-4">
                  <label for="slug" class="mb-2 block text-sm font-medium">URL Personalizada</label>
                  <div class="flex items-center">
                    <span class="rounded-l-md border border-r-0 border-input px-3 py-2 text-sm text-gray-500">
                      inovalinks.com/
                    </span>
                    <input
                      id="slug"
                      v-model="form.slug"
                      type="text"
                      class="flex-1 rounded-r-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                      placeholder="seu-nome"
                    />
                  </div>
                  <div v-if="form.errors.slug" class="mt-1 text-sm text-red-500">
                    {{ form.errors.slug }}
                  </div>
                </div>

                <div class="space-y-4">
                  <label class="mb-2 block text-sm font-medium">Tema</label>
                  <div class="grid grid-cols-3 gap-3 sm:grid-cols-6">
                    <button
                      v-for="theme in themes"
                      :key="theme.id"
                      type="button"
                      @click="form.theme = theme.id"
                      class="aspect-square h-12 rounded-md border border-gray-200 transition focus:outline-none focus:ring-2 focus:ring-ring"
                      :class="[
                        theme.bg,
                        form.theme === theme.id ? 'ring-2 ring-primary' : '',
                      ]"
                    />
                  </div>
                </div>

                <div class="space-y-4">
                  <label class="mb-2 block text-sm font-medium">Avatar</label>
                  
                  <div class="flex items-start gap-6">
                    <!-- Prévia do avatar -->
                    <div v-if="avatarPreview" class="relative h-32 w-32 overflow-hidden rounded-full border-2 border-primary">
                      <img :src="avatarPreview" alt="Avatar preview" class="h-full w-full object-cover" />
                    </div>
                    <div v-else class="flex h-32 w-32 items-center justify-center rounded-full bg-gray-200 text-gray-500">
                      <Image class="h-8 w-8" />
                    </div>
                    
                    <!-- Opções de upload -->
                    <div class="flex flex-1 flex-col gap-4">
                      <div>
                        <button 
                          type="button" 
                          @click="triggerFileInput"
                          class="flex items-center gap-2 rounded-md border border-input px-4 py-2 text-sm font-medium transition-colors hover:bg-accent"
                        >
                          <Upload class="h-4 w-4" />
                          <span>Enviar imagem</span>
                        </button>
                        <input
                          ref="fileInputRef"
                          type="file"
                          accept="image/*"
                          class="hidden"
                          @change="handleFileChange"
                        />
                        <div v-if="form.errors.avatar_file" class="mt-1 text-sm text-red-500">
                          {{ form.errors.avatar_file }}
                        </div>
                      </div>
                      
                      <div>
                        <label for="avatar" class="mb-2 block text-sm font-medium">Ou use uma URL de imagem</label>
                        <input
                          id="avatar"
                          v-model="form.avatar"
                          type="url"
                          class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                          placeholder="https://exemplo.com/imagem.jpg"
                        />
                        <div v-if="form.errors.avatar" class="mt-1 text-sm text-red-500">
                          {{ form.errors.avatar }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex justify-end pt-4">
                  <button
                    type="submit"
                    class="rounded-md border-white border-1 px-4 py-2 text-white hover:bg-zinc-800"
                    :disabled="form.processing"
                  >
                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                  </button>
                </div>
              </form>
            </TabsContent>
          </Tabs>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 