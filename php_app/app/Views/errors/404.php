<div class="max-w-md mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
            <i data-lucide="alert-octagon" class="w-7 h-7"></i>
        </div>
        <h1 class="text-xl font-bold text-slate-900"><?= t('Page Not Found (404)', 'পৃষ্ঠাটি খুঁজে পাওয়া যায়নি (404)') ?></h1>
        <p class="text-xs text-slate-500 leading-relaxed">
            <?= t('The page, doctor profile, or appointment slip you are searching for does not exist or has been moved.', 'আপনি যে পৃষ্ঠা বা ডাক্তারের তথ্য খুঁজছেন তা মুছে ফেলা হয়েছে বা ঠিকানা ভুল রয়েছে।') ?>
        </p>
        <div class="pt-2">
            <a href="/" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition cursor-pointer">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span><?= t('Back to Homepage', 'হোমপেজে ফিরে যান') ?></span>
            </a>
        </div>
    </div>
</div>
