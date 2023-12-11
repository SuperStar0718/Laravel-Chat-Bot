<script setup>
import { ref, defineProps } from 'vue';
const isModalOpen = ref(false);
const props = defineProps({
    message: Object
})

const toggleModal = () => {
    isModalOpen.value = !isModalOpen.value;

}


const emit = defineEmits(['deleteMessage']);

const deleteThisMessage = () => {
    emit('deleteMessage', props.message);
};


</script>

<template>
    <div class="flex items-start max-w-lg pt-3 space-x-3" :class="props.message.sender === 'ChatGPT' && 'self-end'">
        <img :src="props.message.sender === 'ChatGPT' ? 'images/chatgpt.png' : 'images/user.jpg'"
            class="w-12 h-12 ml-3 rounded-full" :class="props.message.sender === 'ChatGPT' && 'order-2'"
            :alt="props.message.sender" />
        <div class="flex-1 order-1">
            <div class="px-4 py-4 rounded-md lg:px-6 lg:pb-6 dark:text-gray-100" :class="props.message.sender === 'ChatGPT'
                ? 'bg-indigo-400 dark:bg-indigo-600'
                : 'bg-gray-200 dark:bg-gray-900'">
                <div class="relative flex items-end justify-between pb-3">
                    <span class="text-sm font-semibold">{{ props.message.sender }}</span>
                    <div class="relative flex items-center space-x-1 ">
                        <div class="w-6 h-6 cursor-pointer hover:bg-gray-400 rounded-xl" @click="toggleModal">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM19 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z">
                                </path>
                            </svg>
                        </div>


                        <div v-if="isModalOpen" class="absolute modal top-6">
                            <!-- Modal content here -->
                            <div class="px-4 py-1 bg-gray-400 cursor-pointer modal-content rounded-xl"
                                @click="deleteThisMessage">
                                <p>Delete</p>
                            </div>
                        </div>
                        <!-- <span class="block text-xs font-semibold tracking-wider uppercase">
                            Just now
                        </span> -->
                    </div>
                </div>
                <p class="block text-sm">{{ props.message.content }}</p>
            </div>
        </div>
    </div>
</template>
