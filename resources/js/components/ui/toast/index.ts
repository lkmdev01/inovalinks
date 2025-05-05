import { defineComponent, h } from 'vue';

const Toast = defineComponent({
  name: 'Toast',
  props: {
    class: { type: String, default: '' },
    variant: {
      type: String,
      default: 'default',
      validator: (value: string) => ['default', 'destructive'].includes(value),
    },
  },
  setup(props, { slots }) {
    return () => h(
      'div',
      {
        class: `group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all data-[swipe=cancel]:translate-x-0 data-[swipe=end]:translate-x-[var(--radix-toast-swipe-end-x)] data-[swipe=move]:translate-x-[var(--radix-toast-swipe-move-x)] data-[swipe=move]:transition-none data-[state=open]:animate-in data-[state=closed]:animate-out data-[swipe=end]:animate-out data-[state=closed]:fade-out-80 data-[state=closed]:slide-out-to-right-full data-[state=open]:slide-in-from-top-full data-[state=open]:sm:slide-in-from-bottom-full ${props.variant === 'destructive'
            ? 'destructive group border-destructive bg-destructive text-destructive-foreground'
            : 'border-border bg-background text-foreground'
          } ${props.class}`,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

const ToastAction = defineComponent({
  name: 'ToastAction',
  props: {
    class: { type: String, default: '' },
  },
  setup(props, { slots, attrs }) {
    return () => h(
      'button',
      {
        class: `inline-flex h-8 shrink-0 items-center justify-center rounded-md border bg-transparent px-3 text-sm font-medium ring-offset-background transition-colors hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 group-[.destructive]:border-muted/40 group-[.destructive]:hover:border-destructive/30 group-[.destructive]:hover:bg-destructive group-[.destructive]:hover:text-destructive-foreground group-[.destructive]:focus:ring-destructive ${props.class}`,
        ...attrs,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

const ToastClose = defineComponent({
  name: 'ToastClose',
  props: {
    class: { type: String, default: '' },
  },
  setup(props, { slots, attrs }) {
    return () => h(
      'button',
      {
        class: `absolute right-2 top-2 rounded-md p-1 text-foreground/50 opacity-0 transition-opacity hover:text-foreground focus:opacity-100 focus:outline-none focus:ring-2 group-hover:opacity-100 group-[.destructive]:text-red-300 group-[.destructive]:hover:text-red-50 group-[.destructive]:focus:ring-red-400 group-[.destructive]:focus:ring-offset-red-600 ${props.class}`,
        ...attrs,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

const ToastDescription = defineComponent({
  name: 'ToastDescription',
  props: {
    class: { type: String, default: '' },
  },
  setup(props, { slots }) {
    return () => h(
      'div',
      {
        class: `text-sm opacity-90 ${props.class}`,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

const ToastTitle = defineComponent({
  name: 'ToastTitle',
  props: {
    class: { type: String, default: '' },
  },
  setup(props, { slots }) {
    return () => h(
      'div',
      {
        class: `text-sm font-semibold ${props.class}`,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

export { Toast, ToastAction, ToastClose, ToastDescription, ToastTitle }; 