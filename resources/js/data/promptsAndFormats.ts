import { TrackingCategory } from '@/enums/trackingCategory';

export type PromptsAndFormats = Record<TrackingCategory, { prompt: string; format: object; }>;

export const promptsAndFormats: PromptsAndFormats = {
    [TrackingCategory.SPENDING]: {
        prompt: `You are a spending record parser. Analyze this sentence and extract information such as vendor, items, amount, date, and category out of a transaction:

        "RECORDED_INPUT"

        IMPORTANT: You must respond with ONLY a valid JSON object that contains the keys (Date, Items, Vendor, Amount,Category), Amount should be a 2 decimal point number

        Rules:
        - return a 2 decimal number for amount
        - if date is not mentioned, make it a date string of today, today is TODAY always return the date in format of yyyy-mm-dd
        - infer category, category must fall into one of the following: CATEGORIES
        - The JSON must be valid and parseable, if the value is a string, make sure it's wrapped in double quotes
        - Example: If someone says "I spent 7.45 on coffee at Lucero", return  {"Amount": 7.45, "Items": "coffee", "Vendor": "Lucero", "Date": "2025-10-13", "Category": "eat-out"}
        `,

        format: {
            type: 'object',
            properties: {
                Date: {
                    type: 'string',
                },
                Amount: {
                    type: 'number',
                },
                Items: {
                    type: 'string',
                },
                Vendor: {
                    type: 'string',
                },
                Category: {
                    type: 'string',
                },
            },
            required: ['Date', 'Items', 'Vendor', 'Amount', 'Category'],
        },
    },
    [TrackingCategory.MACRO]: {
        prompt: `You are a nutrition parser. Analyze this sentence and extract macronutrient information:

            "RECORDED_INPUT"

            IMPORTANT: You must respond with ONLY a valid JSON object in this exact format:
            {"Protein": 0, "Carbs": 0, "Fat": 0, Date: "2025-10-13"}

            Rules:
            - Use ONLY numeric values (no "g" suffix)
            - if date is not mentioned, make it a date string of today, today is TODAY, always return the date in format of yyyy-mm-dd
            - If a nutrient is not mentioned, use 0
            - Do not include any text, explanation, or formatting outside the JSON
            - The JSON must be valid and parseable
            - Example: If someone says "I ate 20g protein", respond with {"Protein": 20, "Carbs": 0, "Fat": 0, Date: "2025-10-13"}
        `,
        format:  {
            type: 'object',
                properties: {
                Protein: {
                    type: 'number',
                },
                Carbs: {
                    type: 'number',
                },
                Fat: {
                    type: 'number',
                },
                Date: {
                    type: 'string',
                },
            },
            required: ['Protein', 'Carbs', 'Fat', 'Date'],
        },
    }
};
