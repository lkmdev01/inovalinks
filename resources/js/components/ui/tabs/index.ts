import { defineComponent, h, provide, inject, ref, Ref } from 'vue';

interface TabsContextType {
  activeTab: Ref<string>;
  setActiveTab: (value: string) => void;
}

const TabsContext = Symbol('TabsContext');

const TabsRoot = defineComponent({
  name: 'Tabs',
  props: {
    defaultValue: { type: String, required: false },
    class: { type: String, default: '' },
  },
  setup(props, { slots }) {
    const activeTab = ref(props.defaultValue || '');

    provide(TabsContext, {
      activeTab,
      setActiveTab: (value: string) => {
        activeTab.value = value;
      }
    } as TabsContextType);

    return () => h(
      'div',
      { class: `${props.class}` },
      slots.default ? slots.default() : undefined
    );
  },
});

const TabsList = defineComponent({
  name: 'TabsList',
  props: {
    class: { type: String, default: '' },
  },
  setup(props, { slots }) {
    return () => h(
      'div',
      {
        class: `inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground ${props.class}`,
      },
      slots.default ? slots.default() : undefined
    );
  },
});

const TabsTrigger = defineComponent({
  name: 'TabsTrigger',
  props: {
    value: { type: String, required: true },
    class: { type: String, default: '' },
  },
  setup(props, { slots }) {
    const context = inject<TabsContextType | undefined>(TabsContext);

    if (!context) {
      console.error('TabsTrigger must be used inside a Tabs component');
      return () => null;
    }

    const { activeTab, setActiveTab } = context;

    const isActive = () => activeTab.value === props.value;

    return () => h(
      'button',
      {
        class: `inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 ${isActive() ? 'bg-background text-foreground shadow-sm' : ''} ${props.class}`,
        onClick: () => setActiveTab(props.value),
      },
      slots.default ? slots.default() : undefined
    );
  },
});

const TabsContent = defineComponent({
  name: 'TabsContent',
  props: {
    value: { type: String, required: true },
    class: { type: String, default: '' },
  },
  setup(props, { slots }) {
    const context = inject<TabsContextType | undefined>(TabsContext);

    if (!context) {
      console.error('TabsContent must be used inside a Tabs component');
      return () => null;
    }

    const { activeTab } = context;

    return () => {
      if (activeTab.value !== props.value) {
        return null;
      }

      return h(
        'div',
        {
          class: `mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ${props.class}`,
        },
        slots.default ? slots.default() : undefined
      );
    };
  },
});

export { TabsRoot as Tabs, TabsList, TabsTrigger, TabsContent }; 