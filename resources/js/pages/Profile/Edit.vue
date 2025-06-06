<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from '@/components/ui/textarea';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { computed, ref, watch } from 'vue';
import { Upload, Image, ArrowRight, ArrowDown, ArrowUp, ArrowLeft } from 'lucide-vue-next';

interface Profile {
  id: number;
  user_id: number;
  title: string;
  description: string | null;
  slug: string;
  avatar: string | null;
  theme: string;
  font: string;
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

// Verificar se o avatar é um caminho de storage local ou uma URL externa
const isLocalStorageAvatar = computed(() => {
  return props.profile.avatar?.startsWith('/storage/');
});

const form = useForm({
  title: props.profile.title || '',
  description: props.profile.description || '',
  slug: props.profile.slug,
  theme: props.profile.theme || 'default',
  font: props.profile.font || 'inter',
  // Se for um avatar de storage local, não mostra no campo de URL
  avatar: isLocalStorageAvatar.value ? '' : (props.profile.avatar || ''),
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
      // Quando um arquivo é selecionado, limpa o campo de URL
      form.avatar = '';
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
  { id: 'default', name: 'Padrão', bg: 'bg-white', text: 'text-gray-900', border: 'border-gray-200' },
  { id: 'dark', name: 'Escuro', bg: 'bg-gray-900', text: 'text-white', border: 'border-gray-700' },
  { id: 'blue', name: 'Azul', bg: 'bg-blue-100', text: 'text-blue-900', border: 'border-blue-200' },
  { id: 'green', name: 'Verde', bg: 'bg-green-100', text: 'text-green-900', border: 'border-green-200' },
  { id: 'purple', name: 'Roxo', bg: 'bg-purple-100', text: 'text-purple-900', border: 'border-purple-200' },
  { id: 'pink', name: 'Rosa', bg: 'bg-pink-100', text: 'text-pink-900', border: 'border-pink-200' },
  { id: 'gray', name: 'Cinza', bg: 'bg-gray-100', text: 'text-gray-900', border: 'border-gray-200' },
  { id: 'red', name: 'Vermelho', bg: 'bg-red-100', text: 'text-red-900', border: 'border-red-200' },
  { id: 'yellow', name: 'Amarelo', bg: 'bg-yellow-100', text: 'text-yellow-900', border: 'border-yellow-200' },
  { id: 'indigo', name: 'Índigo', bg: 'bg-indigo-100', text: 'text-indigo-900', border: 'border-indigo-200' },
  { id: 'teal', name: 'Turquesa', bg: 'bg-teal-100', text: 'text-teal-900', border: 'border-teal-200' },
  { id: 'blue-dark', name: 'Azul Escuro', bg: 'bg-blue-900', text: 'text-white', border: 'border-blue-700' },
  { id: 'green-dark', name: 'Verde Escuro', bg: 'bg-green-900', text: 'text-white', border: 'border-green-700' },
  { id: 'purple-dark', name: 'Roxo Escuro', bg: 'bg-purple-900', text: 'text-white', border: 'border-purple-700' },
  { id: 'pink-dark', name: 'Rosa Escuro', bg: 'bg-pink-900', text: 'text-white', border: 'border-pink-700' },
  { id: 'amber', name: 'Âmbar', bg: 'bg-amber-100', text: 'text-amber-900', border: 'border-amber-200' },
  { id: 'lime', name: 'Lima', bg: 'bg-lime-100', text: 'text-lime-900', border: 'border-lime-200' },
  { id: 'cyan', name: 'Ciano', bg: 'bg-cyan-100', text: 'text-cyan-900', border: 'border-cyan-200' },
];

const fonts = [
  { id: 'inter', name: 'Inter', description: 'Moderna e limpa' },
  { id: 'roboto', name: 'Roboto', description: 'Versátil e legível' },
  { id: 'lato', name: 'Lato', description: 'Elegante e equilibrada' },
  { id: 'open-sans', name: 'Open Sans', description: 'Amigável e neutra' },
  { id: 'montserrat', name: 'Montserrat', description: 'Contemporânea e geométrica' },
  { id: 'poppins', name: 'Poppins', description: 'Moderna com cantos arredondados' },
  { id: 'raleway', name: 'Raleway', description: 'Elegante e minimalista' },
  { id: 'playfair-display', name: 'Playfair Display', description: 'Clássica e sofisticada' },
  { id: 'nunito', name: 'Nunito', description: 'Arredondada e amigável' },
  { id: 'merriweather', name: 'Merriweather', description: 'Serifa para leitura' },
];

function getFontStyle(fontId: string): string {
  return `font-${fontId}`;
}

function triggerFileInput() {
  if (fileInputRef.value) {
    fileInputRef.value.click();
  }
}

function handleFileChange(event: Event) {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files.length > 0) {
    form.avatar_file = input.files[0];
    // Quando um arquivo é selecionado, limpa o campo de URL
    form.avatar = '';
  }
}

function submit() {
  // Se não houver um novo arquivo e já existe um avatar, garante que o campo avatar não seja apagado
  if (!form.avatar_file && avatarPreview.value && !form.avatar) {
    form.avatar = props.profile.avatar || '';
  }

  form.post('/profile', {
    forceFormData: true,
    onSuccess: () => {
      // Limpa o arquivo após o sucesso para evitar conflitos
      form.avatar_file = null;
      
      // Se o resultado for um avatar de storage, limpa o campo de URL para a próxima edição
      if (props.profile.avatar?.startsWith('/storage/')) {
        form.avatar = '';
      }
    }
  });
}

// Gradiente base sem a direção
const gradientBaseThemes = [
  { id: 'blue-purple', name: 'Azul-Roxo', from: 'from-blue-500', to: 'to-purple-500', border: 'border-blue-300' },
  { id: 'green-blue', name: 'Verde-Azul', from: 'from-green-400', to: 'to-blue-500', border: 'border-green-300' },
  { id: 'pink-orange', name: 'Rosa-Laranja', from: 'from-pink-500', to: 'to-orange-400', border: 'border-pink-300' },
  { id: 'indigo-cyan', name: 'Índigo-Ciano', from: 'from-indigo-500', to: 'to-cyan-400', border: 'border-indigo-300' },
  { id: 'purple-pink', name: 'Roxo-Rosa', from: 'from-purple-600', to: 'to-pink-500', border: 'border-purple-300' },
  { id: 'yellow-red', name: 'Amarelo-Vermelho', from: 'from-yellow-400', to: 'to-red-500', border: 'border-yellow-300' },
  { id: 'red-black', name: 'Vermelho-Preto', from: 'from-red-700', to: 'to-gray-900', border: 'border-red-800' },
  { id: 'dark-blue', name: 'Preto-Azul', from: 'from-gray-900', to: 'to-blue-900', border: 'border-blue-900' },
  { id: 'dark-purple', name: 'Cinza-Roxo', from: 'from-gray-800', to: 'to-purple-900', border: 'border-purple-900' },
  { id: 'metal', name: 'Metálico', from: 'from-gray-400', to: 'to-gray-600', border: 'border-gray-500' },
  { id: 'steel', name: 'Aço', from: 'from-slate-400', to: 'to-slate-600', border: 'border-slate-500' },
  { id: 'black-gold', name: 'Preto-Dourado', from: 'from-gray-900', to: 'to-yellow-600', border: 'border-yellow-700' },
];

// Definindo a interface para os temas de gradiente
interface GradientTheme {
  id: string;
  name: string;
  from: string;
  to: string;
  border: string;
}

// Função para obter a classe completa do gradiente com direção
function getGradientClass(baseTheme: GradientTheme): string {
  return `bg-gradient-to-r ${baseTheme.from} ${baseTheme.to}`;
}

// Função para atualizar o tema quando um gradiente é selecionado
function selectGradient(baseTheme: GradientTheme): void {
  form.theme = `gradient-${baseTheme.id}`;
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
          class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
        >
          Ver Página Pública
        </Link>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
        <div class="col-span-1 lg:col-span-5">
          <Tabs default-value="info" class="w-full">
            <TabsList class="mb-4">
              <TabsTrigger value="info">Informações</TabsTrigger>
              <TabsTrigger value="theme">Tema</TabsTrigger>
              <TabsTrigger value="fonts">Fontes</TabsTrigger>
            </TabsList>
            <TabsContent value="info">
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
                  <label class="mb-2 block text-sm font-medium">Avatar</label>
                  <div class="flex items-start gap-6">
                    <div v-if="avatarPreview" class="relative h-32 w-32 overflow-hidden rounded-full border-2 border-primary">
                      <img :src="avatarPreview" alt="Avatar preview" class="h-full w-full object-cover" />
                    </div>
                    <div v-else class="flex h-32 w-32 items-center justify-center rounded-full bg-gray-200 text-gray-500">
                      <Image class="h-8 w-8" />
                    </div>
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
                        <p v-if="isLocalStorageAvatar" class="mt-1 text-xs text-muted-foreground">
                          Você está usando uma imagem carregada. Para substituí-la, envie uma nova imagem ou use uma URL.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex justify-end pt-4">
                  <button
                    type="submit"
                    class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
                    :disabled="form.processing"
                  >
                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                  </button>
                </div>
              </form>
            </TabsContent>
            <TabsContent value="theme">
              <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-6">
                  <div>
                    <h3 class="mb-4 text-lg font-medium">Temas Sólidos</h3>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                      <button
                        v-for="theme in themes.filter(t => !t.id.startsWith('gradient-'))"
                        :key="theme.id"
                        type="button"
                        @click="form.theme = theme.id"
                        class="flex h-24 flex-col items-center justify-center rounded-md border transition focus:outline-none focus:ring-2 focus:ring-ring hover:opacity-90"
                        :class="[
                          theme.bg,
                          theme.text,
                          theme.border,
                          form.theme === theme.id ? 'ring-2 ring-primary shadow-md' : '',
                        ]"
                      >
                        <div class="flex h-full w-full flex-col items-center justify-center">
                          <span class="font-medium">{{ theme.name }}</span>
                          <div class="mt-2 flex space-x-1">
                            <div class="h-2 w-12 rounded-full bg-current opacity-80"></div>
                            <div class="h-2 w-8 rounded-full bg-current opacity-60"></div>
                            <div class="h-2 w-4 rounded-full bg-current opacity-40"></div>
                          </div>
                        </div>
                      </button>
                    </div>
                  </div>
                  
                  <div>
                    <h3 class="mb-4 text-lg font-medium">Gradientes</h3>
                    
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                      <button
                        v-for="baseTheme in gradientBaseThemes"
                        :key="baseTheme.id"
                        type="button"
                        @click="selectGradient(baseTheme)"
                        class="flex h-24 flex-col items-center justify-center rounded-md border transition focus:outline-none focus:ring-2 focus:ring-ring hover:opacity-90"
                        :class="[
                          getGradientClass(baseTheme),
                          'text-white',
                          baseTheme.border,
                          form.theme === `gradient-${baseTheme.id}` ? 'ring-2 ring-primary shadow-md' : '',
                        ]"
                      >
                        <div class="flex h-full w-full flex-col items-center justify-center">
                          <span class="font-medium">{{ baseTheme.name }}</span>
                          <div class="mt-2 flex space-x-1">
                            <div class="h-2 w-12 rounded-full bg-current opacity-80"></div>
                            <div class="h-2 w-8 rounded-full bg-current opacity-60"></div>
                            <div class="h-2 w-4 rounded-full bg-current opacity-40"></div>
                          </div>
                        </div>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="flex justify-end pt-4">
                  <button
                    type="submit"
                    class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
                    :disabled="form.processing"
                  >
                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                  </button>
                </div>
              </form>
            </TabsContent>
            <TabsContent value="fonts">
              <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-4">
                  <label class="mb-2 block text-sm font-medium">Fonte</label>
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <button
                      v-for="font in fonts"
                      :key="font.id"
                      type="button"
                      @click="form.font = font.id"
                      class="flex flex-col items-start justify-center rounded-md border border-input p-4 transition focus:outline-none hover:border-primary hover:bg-accent"
                      :class="[form.font === font.id ? 'ring-2 ring-primary border-primary' : '']"
                    >
                      <div class="flex w-full flex-col">
                        <span class="text-lg font-bold" :class="getFontStyle(font.id)">{{ font.name }}</span>
                        <span class="mt-1 text-sm text-muted-foreground">{{ font.description }}</span>
                        <div class="mt-3 space-y-1">
                          <p :class="getFontStyle(font.id)" class="text-base">Exemplo de texto com esta fonte</p>
                          <p :class="getFontStyle(font.id)" class="text-sm">Texto em tamanho menor para comparação</p>
                        </div>
                      </div>
                    </button>
                  </div>
                </div>
                <div class="flex justify-end pt-4">
                  <button
                    type="submit"
                    class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
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