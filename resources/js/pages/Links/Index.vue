<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { PlusCircle, Trash2, Pencil, GripVertical } from 'lucide-vue-next';
import DynamicIcon from '@/components/ui/DynamicIcon.vue';
import { Switch } from '@/components/ui/switch';
import { ref } from 'vue';

interface LinkItem {
  id: number;
  title: string;
  url: string;
  icon: string;
  is_active: boolean;
  order: number;
}

const props = defineProps<{
  links: LinkItem[];
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
];

const form = useForm({
  ordered_ids: [] as number[],
});

const dragging = ref(false);
const draggedItem = ref<LinkItem | null>(null);
const links = ref<LinkItem[]>([...props.links]);

function startDrag(link: LinkItem, event: DragEvent) {
  if (event.dataTransfer) {
    dragging.value = true;
    draggedItem.value = link;
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.dropEffect = 'move';
  }
}

function onDrop(targetLink: LinkItem, event: DragEvent) {
  event.preventDefault();
  if (draggedItem.value && draggedItem.value.id !== targetLink.id) {
    const draggedIndex = links.value.findIndex(link => link.id === draggedItem.value?.id);
    const targetIndex = links.value.findIndex(link => link.id === targetLink.id);
    
    if (draggedIndex !== -1 && targetIndex !== -1) {
      // Reordenar os itens
      const itemToMove = links.value.splice(draggedIndex, 1)[0];
      links.value.splice(targetIndex, 0, itemToMove);
      
      // Preparar os IDs ordenados para enviar ao servidor
      const orderedIds = links.value.map(link => link.id);
      form.ordered_ids = orderedIds;
      form.post('/links/order', {
        preserveScroll: true,
        onSuccess: () => {
          // Já atualizado no server, não precisa fazer nada
        }
      });
    }
  }
  dragging.value = false;
  draggedItem.value = null;
}

function allowDrop(event: DragEvent) {
  event.preventDefault();
}

function toggleActive(link: LinkItem) {
  useForm({
    title: link.title,
    url: link.url,
    icon: link.icon,
    is_active: !link.is_active,
  }).put(`/links/${link.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      // Atualizando o link localmente para responsividade imediata
      const linkIndex = links.value.findIndex(l => l.id === link.id);
      if (linkIndex !== -1) {
        links.value[linkIndex].is_active = !links.value[linkIndex].is_active;
      }
    }
  });
}

function deleteLink(id: number) {
  // Primeiro remover o link da interface para resposta imediata
  const index = links.value.findIndex(link => link.id === id);
  if (index !== -1) {
    const removedLink = links.value.splice(index, 1)[0];
    
    // Então enviar a requisição para o servidor
    fetch(`/links/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-Inertia': 'true',
      },
    }).catch(() => {
      // Em caso de erro, reinsere o link na lista
      links.value.splice(index, 0, removedLink);
    });
  }
}
</script>

<template>
  <Head title="Seus Links" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-4">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Seus Links</h1>
        <Link
          href="/links/create"
          class="flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
        >
          <PlusCircle class="h-4 w-4" />
          <span>Novo Link</span>
        </Link>
      </div>

      <div class="rounded-lg border bg-card">
        <div class="p-4">
          <div v-if="links.length === 0" class="py-8 text-center text-muted-foreground">
            Você ainda não tem links. Crie seu primeiro link clicando no botão acima.
          </div>
          <ul v-else class="space-y-2">
            <li
              v-for="link in links"
              :key="link.id"
              class="flex items-center justify-between rounded-md border p-3 transition-colors hover:bg-accent"
              draggable="true"
              @dragstart="startDrag(link, $event)"
              @dragover="allowDrop($event)"
              @drop="onDrop(link, $event)"
            >
              <div class="flex items-center gap-3">
                <GripVertical class="h-4 w-4 cursor-move text-muted-foreground" />
                <DynamicIcon :name="link.icon || 'link'" class="h-5 w-5" />
                <div>
                  <h3 class="font-medium">{{ link.title }}</h3>
                  <p class="text-sm text-muted-foreground">{{ link.url }}</p>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <Switch
                  :checked="link.is_active"
                  @update:checked="() => toggleActive(link)"
                />
                <Link
                  :href="`/links/${link.id}/edit`"
                  class="rounded-md p-2 text-muted-foreground hover:bg-secondary"
                >
                  <Pencil class="h-4 w-4" />
                </Link>
                <button
                  @click="deleteLink(link.id)"
                  class="rounded-md p-2 text-muted-foreground hover:bg-destructive hover:text-destructive-foreground"
                >
                  <Trash2 class="h-4 w-4" />
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 