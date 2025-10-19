import { promptsAndFormats } from '@/data/promptsAndFormats';
import { TrackingCategory } from '@/enums/trackingCategory';

export const usePromptHelper = (trackingCategory: TrackingCategory) => {
    const getPromptAndFormat = (recordedInput: string, categories: string[] = []) => {
        const info = promptsAndFormats[trackingCategory];
        return {
            prompt: getPrompt(info!.prompt, recordedInput, categories),
            format: info!.format,
        };
    };

    const getPrompt = (prompt: string, recordedInput: string, categories: string[]) => {
        let filledPrompt = prompt.replace('RECORDED_INPUT', recordedInput);
        if (categories.length > 0) {
            filledPrompt = filledPrompt.replace('CATEGORIES', categories.join(', '))
        }
        return filledPrompt.replace(
            'TODAY',
            new Date().toLocaleDateString('en-CA', {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
            }),
        );
    };
    return { getPromptAndFormat };
};


