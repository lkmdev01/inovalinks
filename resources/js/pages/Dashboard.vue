<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Link as LinkIcon, Palette } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Bem-vindo ao seu Painel de Links</h1>
            <p class="text-muted-foreground">Gerencie seus links e personalize seu perfil.</p>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <Link
                    href="/links"
                    class="flex flex-col items-center justify-center rounded-xl border border-sidebar-border/70 p-6 transition-colors hover:bg-accent dark:border-sidebar-border"
                >
                    <LinkIcon class="mb-3 h-10 w-10 text-primary" />
                    <h2 class="text-lg font-medium">Gerenciar Links</h2>
                    <p class="mt-2 text-center text-sm text-muted-foreground">
                        Adicione, edite ou remova links da sua página
                    </p>
                </Link>

                <Link
                    href="/profile/edit"
                    class="flex flex-col items-center justify-center rounded-xl border border-sidebar-border/70 p-6 transition-colors hover:bg-accent dark:border-sidebar-border"
                >
                    <Palette class="mb-3 h-10 w-10 text-primary" />
                    <h2 class="text-lg font-medium">Personalizar Perfil</h2>
                    <p class="mt-2 text-center text-sm text-muted-foreground">
                        Edite seu perfil e personalize a aparência da sua página
                    </p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
