<template>
  <div>
    <b-modal
      ref="group-invites"
      title="Group Invite"
      size="lg"
      hide-footer
    >
      <div v-if="groupInvites && groupInvites.length > 0">
        <div v-for="groupInvite in groupInvites">
          <div class="friend-drawer ">
            <img
              class="profile-image"
              :src="'images/'+groupInvite.group.image"
              alt=""
            >
            <div class="text">
              <h6 v-if="groupInvite.from.id === currentUser.id">
                You invited <b>{{ groupInvite.to.name }}</b> to join group <b>{{ groupInvite.group.name }}</b>
              </h6>
              <h6 v-else>
                <b>{{ groupInvite.from.name }}</b> invited you to join group <b>{{ groupInvite.group.name }}</b>
              </h6>

              <p v-if="groupInvite.from.id === currentUser.id" class="text-muted">
                {{ groupInvite.to.about }}
              </p>
              <p v-else class="text-muted">
                {{ groupInvite.from.about }}
              </p>
            </div>
            <span class="ml-5">
              <div v-if="groupInvite.from.id === currentUser.id">
                <button class="btn btn-outline-secondary" @click="cancel(groupInvite.id)">Cancel</button>
              </div>

              <div v-else>
                <button class="btn btn-outline-success" @click="approve(groupInvite.id)"><b-icon
                  icon="check-circle"
                /></button>
                <button class="btn btn-outline-danger" @click="reject(groupInvite.id)"><b-icon
                  icon="x-circle"
                /></button>
              </div>
            </span>
          </div>
        </div>
      </div>

      <div v-else>
        <div class="alert alert-info" role="alert">
          There is no group invite yet.
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
name: 'GroupInvitesModal',
    props: ['currentUser'],

    data() {
        return {
            groupInvites: null
        }
    },

    mounted() {
        this.$eventHub.$on('showGroupInvitesModal', this.showGroupInvitesModal);
    },

    methods: {
        showGroupInvitesModal(groupInvites) {
            this.groupInvites = groupInvites;

            this.$refs['group-invites'].show();
        },

        cancel(id) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to cancel this group invites.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.$http.delete('/api/group-invites/' + id + '/cancel');

                        this.$eventHub.$emit('refreshGroupInvites');

                        this.$refs['group-invites'].hide();

                        Vue.$toast.open({
                            message: 'Group Invites Canceled!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });
                    }
                });
        },

        approve(id) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to approve this group invite.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.approveRequest(id);

                        this.$refs['group-invites'].hide()

                        Vue.$toast.open({
                            message: 'Group Invite Approved!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });
                    }
                });
        },

        async approveRequest(id){
            const response = await this.$http.put('/api/group-invites/' + id + '/approve');
            console.log(response.data.data.group)

            this.$eventHub.$emit('groupInviteApproved', response.data.data.group);
        },

        reject(id) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to reject this group invite.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.$http.put('/api/group-invites/' + id + '/reject');

                        this.$eventHub.$emit('refreshGroupInvite');

                        this.$refs['group-invites'].hide()

                        Vue.$toast.open({
                            message: 'Group Invite Rejected!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });
                    }
                });
        }
    }
}
</script>

<style scoped>

</style>
