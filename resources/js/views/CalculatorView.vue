<template>
    <section class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-4 sm:p-6">
            <div class="mb-4 space-y-2">
                <p class="text-xs uppercase tracking-wide text-slate-400">Expression</p>
                <div class="min-h-14 rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-right text-2xl font-semibold break-all">
                    {{ expression || '0' }}
                </div>
                <p class="text-right text-sm text-slate-400">
                    {{ feedback }}
                </p>
            </div>

            <div class="grid grid-cols-4 gap-2">
                <button
                    v-for="key in keys"
                    :key="key.value"
                    type="button"
                    class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-3 text-base font-medium transition hover:bg-slate-700"
                    @click="onKeyPress(key.value)"
                >
                    {{ key.label }}
                </button>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button
                    type="button"
                    class="rounded-xl border border-emerald-600 bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600"
                    @click="submitCalculation"
                >
                    Calculate
                </button>
                <button
                    type="button"
                    class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-semibold transition hover:bg-slate-700"
                    @click="clearExpression"
                >
                    Clear
                </button>
                <button
                    type="button"
                    class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-semibold transition hover:bg-slate-700"
                    @click="backspace"
                >
                    Backspace
                </button>
            </div>
        </article>

        <aside class="rounded-2xl border border-slate-800 bg-slate-900 p-4 sm:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold">Ticker Tape</h2>
                <button
                    type="button"
                    class="rounded-lg border border-rose-700 px-3 py-1.5 text-xs font-semibold text-rose-300 transition hover:bg-rose-900/40"
                    @click="clearHistory"
                    :disabled="history.length === 0"
                >
                    Clear All
                </button>
            </div>

            <ul v-if="history.length" class="space-y-2">
                <li
                    v-for="item in history"
                    :key="item.id"
                    class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950 px-3 py-2"
                >
                    <p class="text-sm text-slate-200">
                        {{ item.expression }} = <span class="font-semibold">{{ item.result }}</span>
                    </p>
                    <button
                        type="button"
                        class="rounded-md border border-slate-700 px-2 py-1 text-xs text-slate-300 transition hover:bg-slate-800"
                        @click="removeHistoryItem(item.id)"
                    >
                        Delete
                    </button>
                </li>
            </ul>

            <p v-else class="text-sm text-slate-400">No calculations yet.</p>
        </aside>
    </section>
</template>

<script setup>
import { ref } from 'vue';

const expression = ref('');
const feedback = ref('Use the keypad and press Calculate.');
const history = ref([]);

const keys = [
    { label: '7', value: '7' },
    { label: '8', value: '8' },
    { label: '9', value: '9' },
    { label: '÷', value: '/' },
    { label: '4', value: '4' },
    { label: '5', value: '5' },
    { label: '6', value: '6' },
    { label: '×', value: '*' },
    { label: '1', value: '1' },
    { label: '2', value: '2' },
    { label: '3', value: '3' },
    { label: '-', value: '-' },
    { label: '0', value: '0' },
    { label: '.', value: '.' },
    { label: '(', value: '(' },
    { label: ')', value: ')' },
    { label: '+', value: '+' },
];

const onKeyPress = (value) => {
    expression.value += value;
};

const clearExpression = () => {
    expression.value = '';
};

const backspace = () => {
    expression.value = expression.value.slice(0, -1);
};

const submitCalculation = async () => {
    const input = expression.value.trim();

    if (!input) {
        feedback.value = 'Enter an expression first.';
        return;
    }

    try {
        const response = await window.axios.post('/api/calculate', { expression: input });
        const result = response.data?.result;

        history.value.unshift({
            id: `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`,
            expression: input,
            result,
        });

        feedback.value = `Result: ${result}`;
        expression.value = '';
    } catch (error) {
        feedback.value = 'Calculation failed. Connect API endpoints when ready.';
    }
};

const removeHistoryItem = (id) => {
    history.value = history.value.filter((item) => item.id !== id);
};

const clearHistory = () => {
    history.value = [];
};
</script>
