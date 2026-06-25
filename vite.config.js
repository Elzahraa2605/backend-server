import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // السطر ده بيقول لـ Vite: "اعتبر الفولدر الحالي هو بداية الكون"
    // هيمنعه يبص على أي حاجة في D:\ أو graduation
    root: process.cwd(), 
    server: {
        fs: {
            strict: true, // تفعيل القيود الصارمة
            allow: ['.']  // مسموح فقط بالملفات اللي جوه final-project
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});