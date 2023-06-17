<template>
  <div>
    <b-modal
      ref="add-group-member"
      title="Add Group Member"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.prevent="submit">
        <b-form-group
          label="Email"
          label-for="email-input"
          :invalid-feedback="form.errors.first('email')"
        >
          <b-form-input
            id="email-input"
            v-model="form.email"
            :state="form.errors.has('email') ? false : null"
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
name: 'AddGroupMemberModal',

    props : {
        selectedGroup: {
            type: Number,
            default: null
        },
    },

    data(){
        return {
            form: new Form({
                    email: null,
                    group_id: null
                },
            ),
        }
    },

    mounted(){
        this.$eventHub.$on('showAddGroupMemberModal',this.showAddGroupMemberModal);
    },

    methods : {
        showAddGroupMemberModal(){
            this.$refs['add-group-member'].show();
        },

        async submit() {
            try {
                this.form.group_id = this.selectedGroup;

                await this.form.post('/api/group-invites');

                this.$refs['add-group-member'].hide();

                Vue.$toast.open({
                    message: 'Group Invite is sent!',
                    type: 'success',
                    position: 'top-right',
                    duration: 600
                });
            } catch (e) {
                if (e.response.status !== 422) {
                    Vue.$toast.open({
                        message: 'An error occurred!',
                        type: 'error',
                        position: 'top-right',
                        duration: 600
                    });
                }
            }
        },

        resetModal() {
            this.form.email = ''
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
