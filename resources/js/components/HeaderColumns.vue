<template>
    <div class="w-full flex flex-wrap tool-header justify-around text-left">
        <!-- 	<div class="w-11 item">
		</div> -->
        <div class="px-3 item">
            {{ __('Label') }}
        </div>
        <div class="px-3 item">
            {{ __('Field') }}
        </div>
        <div class="px-3 item">
            {{ __('Required') }}
        </div>

        <div class="px-3 item">
            {{ __('Sortable') }}
        </div>

        <div class="px-3 item">
            {{ __('Show in') }}
        </div>

        <div class="px-3 item" v-if="hasExtras">
            {{ __('Extras') }}
        </div>
    </div>

</template>

<script>
export default {
    props: {
        hasExtras: {
            type: Boolean,
            default: false,
            required: true,
        },
    },
};
</script>

<style scoped lang="scss">
.tool-header {
    background-color: #f4f7fa;
    border-bottom-width: 2px;
    border-color: #e3e7eb;

    .item {
        background-color: #f4f7fa;
        font-weight: 800;
        font-size: 0.75rem;
        color: #7c858e;
        text-transform: uppercase;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        letter-spacing: 0.05em;
        flex: 1 1 0;
    }
}
</style>
