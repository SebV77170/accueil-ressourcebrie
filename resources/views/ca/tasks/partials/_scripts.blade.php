{{-- Scripts spécifiques à la page --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('taskToggle', (taskId) => ({
            open: false,
            init() {
                const stored = localStorage.getItem(`task-open-${taskId}`);

                if (stored !== null) {
                    this.open = stored === 'true';
                }

                this.$watch('open', (value) => {
                    localStorage.setItem(`task-open-${taskId}`, value);
                });
            },
        }));
    });
</script>
