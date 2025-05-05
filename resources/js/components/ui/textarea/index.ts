import { defineComponent, h } from 'vue';

const Textarea = defineComponent({
  name: 'Textarea',
  props: {
    modelValue: { type: String, default: '' },
    class: { type: String, default: '' },
    placeholder: { type: String, default: '' },
  },
  emits: ['update:modelValue'],
  setup(props, { slots, attrs, emit }) {
    const onInput = (event: Event) => {
      const target = event.target as HTMLTextAreaElement;
      emit('update:modelValue', target.value);
    };

    return () => h(
      'textarea',
      {
        value: props.modelValue,
        placeholder: props.placeholder,
        class: `flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ${props.class}`,
        onInput,
        ...attrs,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

export { Textarea };
export default Textarea; 