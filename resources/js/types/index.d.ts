import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';
import type { VNode, ComponentRenderProxy } from 'vue';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

// Este arquivo adiciona suporte para tipos Vue TSX

declare module 'vue/jsx' {
    export * from 'vue'
}

declare module 'vue/tsx' {
    export * from 'vue'
}

declare namespace JSX {
    interface Element extends VNode { }
    interface ElementClass extends ComponentRenderProxy { }
    interface ElementAttributesProperty {
        $props: {}
    }
    interface IntrinsicElements {
        [elem: string]: any
    }
}
