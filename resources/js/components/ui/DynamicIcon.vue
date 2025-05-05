<script setup lang="ts">
import { getIcon } from './dynamic-icon';
import { computed } from 'vue';

const props = defineProps<{
  name: string;
  size?: number;
  strokeWidth?: number;
  class?: string;
}>();

const IconComponent = computed(() => getIcon(props.name));
</script>

<template>
  <component
    :is="IconComponent"
    :size="size ?? 24"
    :stroke-width="strokeWidth ?? 2"
    :class="class"
  />
</template> 