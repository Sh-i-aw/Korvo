<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Link } from '@inertiajs/vue3';
import { useSpeechRecognition } from '@vueuse/core';
import { useLlama} from '@/composables/useLlama';
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

const { talkToLlama } = useLlama()

// Watch for changes in recognition result and update speechResult
watch(recognition.result, (newResult) => {
    if (newResult) {
        speechResult.value = newResult;
        talkToLlamaAboutMacro();
    }
});

const talkToMe = () => {
    if (isListening.value) {
        recognition.stop();
    } else {
        recognition.start();
    }
    isListening.value = !isListening.value;
};


const sendTextToLlama = async () => {
    await talkToLlamaAboutMacro()
    speechResult.value = ''
}

const talkToLlamaAboutMacro = async () => {
    const prompt = `You are a nutrition parser. Analyze this sentence and extract macronutrient information:

    "${speechResult.value}"

    IMPORTANT: You must respond with ONLY a valid JSON object in this exact format:
    {"Protein": 0, "Carbs": 0, "Fat": 0}

    Rules:
    - Use ONLY numeric values (no "g" suffix)
    - If a nutrient is not mentioned, use 0
    - Do not include any text, explanation, or formatting outside the JSON
    - The JSON must be valid and parseable
    - Example: If someone says "I ate 20g protein", respond with {"Protein": 20, "Carbs": 0, "Fat": 0}
`;

    ollamaResult.value = 'Ollama is processing';

    const responseData = await talkToLlama(prompt)

    // Try to parse the response as JSON
    try {
        const parsedMacros = JSON.parse(responseData);
        if (
            parsedMacros &&
            typeof parsedMacros === 'object' &&
            'Protein' in parsedMacros &&
            'Carbs' in parsedMacros &&
            'Fat' in parsedMacros
        ) {
            ollamaResult.value = `Protein: ${parsedMacros.Protein}g, Carbs: ${parsedMacros.Carbs}g, Fat: ${parsedMacros.Fat}g`;
        } else {
            ollamaResult.value = `Invalid format: ${responseData}`;
        }
    } catch (error: any) {
        ollamaResult.value = `Invalid JSON: ${responseData}`;
        console.log(error.message)
    }
};
</script>

<template>
    <div
        class="flex h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
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
            class="flex flex-col w-full h-full items-center justify-center gap-10 opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >

                <button
                    @click="talkToMe"
                    class="rounded-full border-2 border-slate-800 p-10"
                    :class="
                        isListening
                            ? 'border-slate-200 bg-green-500'
                            : 'bg-slate-200'
                    "
                >
                    <Mic class="h-20 w-20" />
                </button>

                <label>
                    Or put the text here:
                    <input
                        type="text"
                        class="border rounded-md border-blue-300 p-2"
                        v-model="speechResult"
                    />
                </label>
                <button
                    class="bg-blue-200 font-md rounded-md p-2 border border-blue-800"
                    @click="sendTextToLlama"
                >
                    Send to Llama
                </button>

                <div>
                    {{ `You said: "${speechResult}"`}}
                </div>

                <div>{{ `Llama heard: ${ollamaResult}`}}</div>

        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
