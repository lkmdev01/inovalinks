import { defineComponent, h, ref } from 'vue';

const Switch = defineComponent({
  name: 'Switch',
  props: {
    checked: {
      type: Boolean,
      default: false
    },
    class: {
      type: String,
      default: ''
    }
  },
  emits: ['update:checked'],
  setup(props, { emit, slots }) {
    const isChecked = ref(props.checked);

    const toggle = () => {
      isChecked.value = !isChecked.value;
      emit('update:checked', isChecked.value);
    };

    return () => h(
      'button',
      {
        type: 'button',
        role: 'switch',
        'aria-checked': isChecked.value,
        class: `relative inline-flex h-6 w-11 items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 ${isChecked.value ? 'bg-primary' : 'bg-input'} ${props.class}`,
        onClick: toggle,
      },
      h(
        'span',
        {
          class: `pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform ${isChecked.value ? 'translate-x-5' : 'translate-x-0'}`,
        }
      )
    );
  },
});

export { Switch };
export default Switch; 