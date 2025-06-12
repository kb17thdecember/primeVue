import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useAuth() {
    const page = usePage();
    
    const user = computed(() => page.props.auth?.user);
    const role = computed(() => user.value?.role ?? null);
    
    return {
        user,
        role,
        isAdmin: computed(() => role.value === 0),
        isStaff: computed(() => role.value === 1)
    };
} 