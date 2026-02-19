<template>
    <section class="h-full min-h-0 grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-4 sm:p-6">
            <div class="mb-4 space-y-2">
                <p class="text-xs uppercase tracking-wide text-slate-400">Expression</p>
                <div
                    data-test="expression-display"
                    class="min-h-14 rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-right text-2xl font-semibold break-all"
                >
                    {{ expression || '0' }}
                </div>
                <p data-test="feedback" class="text-right text-sm text-slate-400">
                    {{ feedback }}
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div class="grid grid-cols-3 gap-2">
                        <button
                            v-for="key in numbers"
                            :key="key.value"
                            type="button"
                            :data-test="`key-${key.testId}`"
                            class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-3 text-base font-medium transition hover:bg-slate-700"
                            @click="onKeyPress(key.value)"
                        >
                            {{ key.label }}
                        </button>
                    </div>
                </div>

                <div>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            v-for="key in operations"
                            :key="key.value"
                            type="button"
                            :data-test="`key-${key.testId}`"
                            class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-3 text-base font-medium transition hover:bg-slate-700"
                            @click="onKeyPress(key.value)"
                        >
                            {{ key.label }}
                        </button>
                    </div>
                </div>

                <div>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            v-for="fn in functions"
                            :key="fn.value"
                            type="button"
                            :data-test="`key-${fn.testId}`"
                            class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-3 text-sm font-medium transition hover:bg-slate-700"
                            @click="onKeyPress(fn.value)"
                        >
                            {{ fn.label }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button
                    type="button"
                    data-test="calculate-btn"
                    class="rounded-xl border border-emerald-600 bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600"
                    :disabled="isCalculating"
                    @click="submitCalculation"
                >
                    Calculate
                </button>
                <button
                    type="button"
                    data-test="clear-expression-btn"
                    class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-semibold transition hover:bg-slate-700"
                    @click="clearExpression"
                >
                    Clear
                </button>
                <button
                    type="button"
                    data-test="backspace-btn"
                    class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-semibold transition hover:bg-slate-700"
                    @click="backspace"
                >
                    Backspace
                </button>
            </div>
        </article>

        <aside
            class="rounded-2xl border border-slate-800 bg-slate-900 p-4 sm:p-6 flex flex-col min-h-0 overflow-hidden"
        >
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold">Ticker Tape</h2>
                <button
                    type="button"
                    data-test="clear-history-btn"
                    class="rounded-lg border border-rose-700 px-3 py-1.5 text-xs font-semibold text-rose-300 transition hover:bg-rose-900/40"
                    :disabled="history.length === 0 || isClearingHistory"
                    @click="clearHistory"
                >
                    Clear All
                </button>
            </div>

            <ul
                v-if="history.length"
                data-test="history-list"
                class="space-y-2 overflow-y-auto pr-2 flex-1 min-h-0"
            >
                <li
                    v-for="item in history"
                    :key="item.id"
                    :data-test="`history-item-${item.id}`"
                    class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950 px-3 py-2"
                >
                    <p :data-test="`history-expression-${item.id}`" class="text-sm text-slate-200">
                        {{ item.expression }} = <span class="font-semibold">{{ item.result }}</span>
                    </p>
                    <button
                        type="button"
                        :data-test="`delete-history-${item.id}`"
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
import { onMounted, ref } from 'vue';

const expression = ref('');
const feedback = ref('Use the keypad and press Calculate.');
const history = ref([]);
const isCalculating = ref(false);
const isClearingHistory = ref(false);

const numbers = [
    { label: '7', value: '7', testId: '7' },
    { label: '8', value: '8', testId: '8' },
    { label: '9', value: '9', testId: '9' },
    { label: '4', value: '4', testId: '4' },
    { label: '5', value: '5', testId: '5' },
    { label: '6', value: '6', testId: '6' },
    { label: '1', value: '1', testId: '1' },
    { label: '2', value: '2', testId: '2' },
    { label: '3', value: '3', testId: '3' },
    { label: '0', value: '0', testId: '0' },
    { label: '.', value: '.', testId: 'dot' },
];

const operations = [
    { label: '+', value: '+', testId: 'plus' },
    { label: '-', value: '-', testId: 'minus' },
    { label: '×', value: '*', testId: 'multiply' },
    { label: '÷', value: '/', testId: 'divide' },
    { label: '^', value: '^', testId: 'power' },
];

const functions = [
    { label: 'sqrt', value: 'sqrt(', testId: 'sqrt' },
    { label: 'sin', value: 'sin(', testId: 'sin' },
    { label: 'cos', value: 'cos(', testId: 'cos' },
    { label: 'tan', value: 'tan(', testId: 'tan' },
    { label: 'log', value: 'log(', testId: 'log' },
    { label: 'ln', value: 'ln(', testId: 'ln' },
    { label: 'abs', value: 'abs(', testId: 'abs' },
    { label: 'exp', value: 'exp(', testId: 'exp' },
    { label: '(', value: '(', testId: 'lparen' },
    { label: ')', value: ')', testId: 'rparen' },
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

    isCalculating.value = true;

    try {
        const response = await window.axios.post('/api/calculate', { expression: input });
        const item = response.data?.data;

        if (!item?.id) {
            throw new Error('Malformed calculate response');
        }

        history.value = [
            item,
            ...history.value.filter((historyItem) => historyItem.id !== item.id),
        ];

        feedback.value = `Result: ${item.result}`;
        expression.value = item.result.toString();
    } catch (error) {
        const message =
            error?.response?.data?.errors?.expression?.[0] ||
            error?.response?.data?.message ||
            'Calculation failed. Please try again.';

        feedback.value = message;
    } finally {
        isCalculating.value = false;
    }
};

const removeHistoryItem = async (id) => {
    try {
        await window.axios.delete(`/api/history/${id}`);
        history.value = history.value.filter((item) => item.id !== id);
    } catch {
        feedback.value = 'Unable to delete history item right now.';
    }
};

const clearHistory = async () => {
    if (!history.value.length) {
        return;
    }

    isClearingHistory.value = true;

    try {
        await window.axios.delete('/api/history');
        history.value = [];
        feedback.value = 'History cleared.';
    } catch {
        feedback.value = 'Unable to clear history right now.';
    } finally {
        isClearingHistory.value = false;
    }
};

const loadHistory = async () => {
    try {
        const response = await window.axios.get('/api/history');

        history.value = response.data?.data ?? [];
    } catch {
        feedback.value = 'Unable to load history right now.';
    }
};

onMounted(async () => {
    await loadHistory();
});
</script>
