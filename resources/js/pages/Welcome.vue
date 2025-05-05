<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';

interface PageProps {
  auth: {
    user: any;
  };
  [key: string]: any;
}

const page = usePage<PageProps>();
</script>

<template>
    <Head title="InovaLinks">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-white lg:p-8">
        <header class="mb-6 w-full max-w-5xl text-sm">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="page.props.auth.user"
                    href="/dashboard"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        href="/login"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Login
                    </Link>
                    <Link
                        href="/register"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Cadastro
                    </Link>
                </template>
            </nav>
        </header>
        
        <main class="flex w-full max-w-5xl flex-col items-center">
            <!-- Hero Section -->
            <div class="pt-16 pb-26 text-center">
                <h1 class="mb-6 text-5xl font-bold text-black dark:text-white">
                    <span class="text-emerald-600">Inova</span>Links
                </h1>
                <p class="mb-2 text-xl text-gray-700 dark:text-gray-300">
                    Um projeto 100% brasileiro para gerenciar todos os seus links em um só lugar
                </p>
                <p class="mb-8 text-lg text-gray-600 dark:text-gray-400">
                    Simplifique sua presença online com uma página personalizada e de fácil compartilhamento
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <Link
                        v-if="page.props.auth.user"
                        href="/dashboard"
                        class="rounded-md bg-emerald-600 px-6 py-3 font-medium text-white transition hover:bg-emerald-700"
                    >
                        Acessar Dashboard
                    </Link>
                    <Link
                        v-else
                        href="/register"
                        class="rounded-md bg-emerald-600 px-6 py-3 font-medium text-white transition hover:bg-emerald-700"
                    >
                        Começar Agora - É Grátis!
                    </Link>
                </div>
            </div>
            
            <!-- Features Section -->
            <div class="mb-16 grid w-full gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 inline-flex rounded-full bg-emerald-100 p-3 text-emerald-600 dark:bg-emerald-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold dark:text-white">Simplifique sua presença online</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Reúna todos os seus links importantes em uma única página personalizada e de fácil acesso.
                    </p>
                </div>
                
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 inline-flex rounded-full bg-emerald-100 p-3 text-emerald-600 dark:bg-emerald-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold dark:text-white">Personalize sua página</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Escolha entre diversos temas, adicione seu avatar e crie uma experiência única para seus seguidores.
                    </p>
                </div>
                
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 inline-flex rounded-full bg-emerald-100 p-3 text-emerald-600 dark:bg-emerald-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold dark:text-white">Compartilhe com facilidade</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Tenha uma URL personalizada e fácil de lembrar para compartilhar em suas redes sociais.
                    </p>
                </div>
            </div>

            <!-- How to Use Section -->
            <div class="mb-16 w-full rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-6 text-center text-3xl font-bold text-emerald-700 dark:text-emerald-500">Como usar o InovaLinks</h2>
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-600 dark:bg-emerald-900/30">1</div>
                        <h3 class="mb-2 text-lg font-semibold dark:text-white">Crie sua conta</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Registre-se gratuitamente em apenas alguns segundos usando seu e-mail.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-600 dark:bg-emerald-900/30">2</div>
                        <h3 class="mb-2 text-lg font-semibold dark:text-white">Personalize seu perfil</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Adicione seu avatar, escolha as cores e personalize seu nome de usuário.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-600 dark:bg-emerald-900/30">3</div>
                        <h3 class="mb-2 text-lg font-semibold dark:text-white">Adicione seus links</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Cadastre todos os seus links importantes: redes sociais, loja online, blog, portfólio e mais.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="mb-16 w-full rounded-lg bg-emerald-50 p-8 text-center dark:bg-emerald-900/20">
                <h2 class="mb-4 text-3xl font-bold text-emerald-800 dark:text-emerald-400">Ajude a construir o InovaLinks!</h2>
                <p class="mb-6 text-gray-600 dark:text-gray-400">
                    O InovaLinks está em constante evolução e queremos sua participação nessa jornada. Como projeto brasileiro, valorizamos sua opinião para construir uma ferramenta que atenda às necessidades reais dos nossos usuários.
                </p>
                <div class="mb-6 space-y-3 text-left md:px-12">
                    <p class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Que recursos você gostaria de ver implementados?
                    </p>
                    <p class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Como podemos melhorar a experiência do usuário?
                    </p>
                    <p class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Que integrações com outras plataformas brasileiras seriam úteis?
                    </p>
                </div>
                <a href="mailto:feedback@inovalinks.com.br" class="inline-block rounded-md border border-emerald-600 px-6 py-3 font-medium text-emerald-600 transition hover:bg-emerald-600 hover:text-white dark:border-emerald-500 dark:text-emerald-500 dark:hover:bg-emerald-700 dark:hover:text-white">
                    Enviar sugestões
                </a>
            </div>

            <!-- Brazilian Advantage -->
            <div class="mb-16 w-full rounded-lg border-2 border-green-500 bg-white p-8 shadow-sm dark:bg-gray-900">
                <div class="flex flex-col items-center md:flex-row">
                    <div class="mb-6 flex items-center justify-center md:mb-0 md:w-1/3">
                        <div class="h-32 w-32 rounded-full bg-gradient-to-r from-green-500 to-yellow-400 p-1">
                            <div class="flex h-full w-full items-center justify-center rounded-full bg-white text-4xl dark:bg-gray-900">
                                🇧🇷
                            </div>
                        </div>
                    </div>
                    <div class="md:w-2/3 md:pl-8">
                        <h2 class="mb-4 text-2xl font-bold text-green-600 dark:text-green-400">Orgulhosamente Brasileiro</h2>
                        <div class="space-y-3 text-gray-600 dark:text-gray-400">
                            <p>
                                O InovaLinks está sendo desenvolvido no Brasil, por brasileiros e para brasileiros. 
                            </p>
                            <p>
                                Nossa plataforma busca atender às necessidades específicas do público brasileiro, com suporte em português e adaptada à nossa realidade.
                            </p>
                            <p>
                                Ao usar o InovaLinks, você está apoiando o desenvolvimento tecnológico nacional e contribuindo para o crescimento de uma startup brasileira.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="w-full max-w-5xl py-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>© 2024 InovaLinks. Todos os direitos reservados. Feito no Brasil com 💚💛</p>
            <div class="mt-2 flex justify-center space-x-4">
                <a href="#" class="hover:text-emerald-600 dark:hover:text-emerald-400">Termos de Uso</a>
                <a href="#" class="hover:text-emerald-600 dark:hover:text-emerald-400">Política de Privacidade</a>
                <a href="#" class="hover:text-emerald-600 dark:hover:text-emerald-400">Contato</a>
            </div>
        </footer>
    </div>
</template>
