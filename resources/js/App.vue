<template>
    <div class="flex h-screen flex-col overflow-hidden bg-slate-950 text-slate-100">
        <header class="border-b border-slate-800 bg-slate-900/70 backdrop-blur">
            <div class="mx-auto w-full max-w-6xl px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-lg font-semibold tracking-tight">CalcTeck Calculator</h1>
                <p class="text-sm text-slate-400">API-driven calculator interface</p>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 min-h-0 px-4 py-6 sm:px-6 lg:px-8">
            <RouterView />
        </main>
    </div>
</template>

<script setup></script>
