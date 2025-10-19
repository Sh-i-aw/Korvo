export const talkToLlama = async (prompt: string, format: object) => {
    try {
        const response = await fetch('/api/ollama/generate', {
            method: 'POST',
            headers: { 'Content-Type': "application/json" },
            body: JSON.stringify({
                model: 'gemma3:1b',
                prompt: prompt,
                format: format,
                stream: false,
                options: {
                    temperature: 0.1, // Lower temperature for more consistent output
                    top_p: 0.9, // Focus on most likely tokens
                    stop: ['\n\n', '```', 'Explanation:', 'Note:'], // Stop at common non-JSON patterns
                },
            }),
        });

        if (!response.ok) {
            console.error('Response status:', response.status);
            return await response
                .json()
                .catch(() => ({ error: 'Unknown error' }));
        }

        return await response.json();
    } catch (error) {
        console.error('Error calling Ollama:', error);
        return error
    }
}

export function useLlama() {
    return { talkToLlama };
}
