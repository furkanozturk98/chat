<template>
  <div>
    <b-modal
      ref="edit-message"
      title="Edit Message"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.prevent="submit">
        <b-form-group
          label="Message"
          label-for="message-input"
          :invalid-feedback="form.errors.first('message')"
        >
          <b-form-input
            id="message-input"
            v-model="form.message"
            :state="form.errors.has('message') ? false : null"
            required
          />
        </b-form-group>
      </form>
    </b-modal>
  </div>
</template>

<script>
import Form from 'form-backend-validation';

export default {
    name: 'EditMessageModal',

    data() {
        return {
            form: new Form({
                    message: null
                },
            ),
            data: null
        }
    },

    mounted() {
        this.$eventHub.$on('showEditMessageModal', this.showEditMessageModal);
    },

    methods: {

        showEditMessageModal(data) {
            this.$refs['edit-message'].show();
            this.form.message = data.message;
            this.data = data;
        },

        async submit() {
            const data = {
                'id' : this.data.id,
                'message' : this.form.message
            }

            await this.form.put('/api/message/update/' + this.data.id)
                .then(() => {

                    Vue.$toast.open({
                        message: 'Message is edited successfully',
                        type: 'success',
                        position: 'top-right',
                        duration: 600
                    });

                    this.$eventHub.$emit('messageEdited',data);
                })
                .catch(() => {
                    Vue.$toast.open({
                        message: 'An error occurred.',
                        type: 'error',
                        position: 'top-right',
                        duration: 1000
                    });
                });

            this.$refs['edit-message'].hide();
        },

        resetModal() {
            this.form.message = ''
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();

            this.submit();
        },
    }
}
</script>

<style scoped>

</style>
