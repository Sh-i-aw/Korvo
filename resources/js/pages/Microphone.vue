<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { useSpeechRecognition } from '@vueuse/core';
import { Mic } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const isListening = ref<boolean>(false);
const ollamaResult = ref<string>('nothing yet');
const speechResult = ref<string>('nothing yet');

const recognition = useSpeechRecognition({
    interimResults: false,
    continuous: true,
    lang: 'en-CA',
});

// Watch for changes in recognition result and update speechResult
watch(recognition.result, (newResult) => {
    if (newResult) {
        speechResult.value = newResult;
    }
});

const talkToMe = () => {
    if (isListening.value) {
        recognition.stop();
        talkToLlama();
    } else {
        recognition.start();
    }
    isListening.value = !isListening.value;
};

const talkToLlama = async () => {
    const prompt = ` Given the following sentence:
        ${speechResult.value}
        parse out what type of macro the person has eaten, and how many grams,
        only return the type of protein and grams, 2 words is sufficient as a response`;
    console.log(prompt);

    try {
        ollamaResult.value = 'Ollama is processing'
        const response = await fetch('/api/ollama/generate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                model: 'llama3.2',
                prompt: prompt,
                stream: false,
            }),
        });

        if (!response.ok) {
            console.log('Response status:', response.status);
            ollamaResult.value = `Error: ${response.status}`;
            return;
        }

        const responseText = await response.text();
        ollamaResult.value = responseText || 'No response received';
    } catch (error) {
        console.error('Error calling Ollama:', error);
        ollamaResult.value = 'Error: Failed to get response from Ollama';
    }
};
</script>

<template>
    <Head title="Welcome">
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header
            class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="register()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main class="flex-col items-center justify-center">
                <button
                    @click="talkToMe"
                    class="rounded-full border-2 border-slate-800 p-10"
                    :class="isListening ? 'bg-green-500' : 'bg-red-500'"
                >
                    <Mic class="h-20 w-20" />
                </button>
                <p>
                    {{ recognition.result }}
                </p>

                <p>{{ ollamaResult }}</p>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
