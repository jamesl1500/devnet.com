@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>
        
        <!-- Compact Post Creation Component -->
        <div class="mb-8">
            <x-dashboard.post-creation :compact="true" />
        </div>
        
        <!-- Example Posts Feed -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&color=7F9CF5&background=EBF4FF" 
                         alt="John Doe" 
                         class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-semibold text-gray-900">John Doe</h3>
                            <span class="text-gray-500 text-sm">@johndoe</span>
                            <span class="text-gray-400 text-sm">• 2h ago</span>
                        </div>
                        <p class="text-gray-700 mb-3">
                            Just finished implementing an amazing new feature! The hover-to-expand functionality 
                            for the post creation form works beautifully. What do you all think?
                        </p>
                        <div class="flex items-center gap-6 text-gray-500 text-sm">
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-heart"></i>
                                <span>24</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-comment"></i>
                                <span>8</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-share"></i>
                                <span>3</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&color=F56565&background=FED7D7" 
                         alt="Jane Smith" 
                         class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-semibold text-gray-900">Jane Smith</h3>
                            <span class="text-gray-500 text-sm">@janesmith</span>
                            <span class="text-gray-400 text-sm">• 4h ago</span>
                        </div>
                        <p class="text-gray-700 mb-3">
                            Working on some exciting updates to the platform! The new post creation experience 
                            is going to be game-changing. Can't wait to share more details soon! 🚀
                        </p>
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <img src="https://picsum.photos/300/200?random=1" 
                                 alt="Preview" 
                                 class="rounded-lg w-full h-32 object-cover">
                            <img src="https://picsum.photos/300/200?random=2" 
                                 alt="Preview" 
                                 class="rounded-lg w-full h-32 object-cover">
                        </div>
                        <div class="flex items-center gap-6 text-gray-500 text-sm">
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-heart"></i>
                                <span>42</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-comment"></i>
                                <span>15</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-share"></i>
                                <span>7</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <img src="https://ui-avatars.com/api/?name=Mike+Johnson&color=48BB78&background=C6F6D5" 
                         alt="Mike Johnson" 
                         class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-semibold text-gray-900">Mike Johnson</h3>
                            <span class="text-gray-500 text-sm">@mikejohnson</span>
                            <span class="text-gray-400 text-sm">• 6h ago</span>
                        </div>
                        <p class="text-gray-700 mb-3">
                            Pro tip: Try hovering over the post creation box at the top of the page! 
                            The smooth expansion animation is so satisfying. Great UX work by the team! 👏
                        </p>
                        <div class="flex items-center gap-6 text-gray-500 text-sm">
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-heart"></i>
                                <span>18</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-comment"></i>
                                <span>5</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500">
                                <i class="fas fa-share"></i>
                                <span>2</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button for Mobile -->
        <button onclick="openPostModal()" 
                class="fixed bottom-6 right-6 w-14 h-14 bg-gradient-to-r from-cyan-400 to-cyan-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center lg:hidden">
            <i class="fas fa-plus text-xl"></i>
        </button>
    </div>
</div>

<style>
/* Additional dashboard-specific styles */
.container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Floating button animation */
.fixed.bottom-6.right-6 {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hover effects for post cards */
.bg-white.border.border-gray-200:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}
</style>
@endsection