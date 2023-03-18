<template>
  <div>
    <add-friend-modal :current-user="currentUser" />
    <create-group-modal :current-user="currentUser" />
    <friend-request-modal :current-user="currentUser" />
    <group-invites-modal :current-user="currentUser" />
    <profile-settings-modal :current-user="currentUser" />

    <div class="row no-gutters">
      <div class="col-md-3 border-right" :class="{'friend-dark': nightMode}">
        <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
          <img
            class="profile-image"
            :src="'images/'+currentUser.image+'?rand='+rand"
            alt="Profile img"
          >
          <span
            :class="{'text-white': nightMode}"
          >
            {{ currentUser.name }}
          </span>

          <span
            class="
            settings-tray--right"
          >
            <i class="material-icons" @click="showAddPersonModal">person_add</i>
            <i class="material-icons" @click="toggleDropDown">menu</i>

            <b-dropdown ref="dropdown" size="lg" variant="link" toggle-class="text-decoration-none" no-caret>
              <b-dropdown-item @click="showFriendRequestsModal">
                <b-icon icon="person-lines-fill" aria-hidden="true" />
                Friend requests
              </b-dropdown-item>
              <b-dropdown-item @click="showGroupInvitesModal">
                <b-icon icon="person-lines-fill" aria-hidden="true" />
                Group Invites
              </b-dropdown-item>
              <b-dropdown-item @click="showProfileSettingsModal">
                <b-icon icon="gear-fill" aria-hidden="true" />
                Profile Settings
              </b-dropdown-item>
              <b-dropdown-item @click="nightModeOn">
                <b-icon icon="moon" aria-hidden="true" />
                Night Mode
              </b-dropdown-item>
              <b-dropdown-item
                href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              >
                <b-icon icon="door-closed-fill" aria-hidden="true" />
                Sign Out
              </b-dropdown-item>
              <b-form id="logout-form" action="logout" method="POST" style="display: none;">
                <input type="hidden" name="_token" :value="csrf">
              </b-form>
            </b-dropdown>

          </span>
        </div>

        <div class="search-box" :class="{'search-box-dark': nightMode }">
          <div class="input-wrapper" :class="{'input-wrapper-dark': nightMode}">
            <i class="material-icons">search</i>
            <input v-model="search" type="text" placeholder="Search here">
          </div>
        </div>

        <ul id="myTab" class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a
              id="friend-tab"
              class="nav-link active"
              :style="[nightMode ? '' : {'background-color':'white'}]"
              data-toggle="tab"
              href="#friend"
              role="tab"
              aria-controls="friend"
              aria-selected="true"
              @click="handleFriendsTabClick"
            >Friends</a>
          </li>
          <li class="nav-item">
            <a
              id="group-tab"
              class="nav-link"
              :style="[nightMode ? '' : {'background-color':'white'}]"
              data-toggle="tab"
              href="#group"
              role="tab"
              aria-controls="group"
              aria-selected="false"
              @click="handleGroupsTabClick"
            >Groups</a>
          </li>

          <li v-show="visibleSaveButton" role="presentation" class="nav-item ml-auto">
            <button class="btn btn-primary btn-sm float-right mr-2 mt-1" @click="showCreateGroupModal">
              <b-icon icon="person-plus-fill" aria-hidden="true" />
            </button>
          </li>
        </ul>

        <div id="myTabContent" class="tab-content">
          <div id="friend" class="tab-pane fade show active" role="tabpanel" aria-labelledby="friend-tab">
            <chat-friend-list :night-mode="nightMode" :items="filteredItems" />
          </div>

          <div id="group" class="tab-pane fade" role="tabpanel" aria-labelledby="group-tab">
            <chat-group-list v-if="visibleSaveButton" :night-mode="nightMode" :items="filteredItems" />
          </div>
        </div>
      </div>

      <chat-conversation-screen v-show="selectedFriend" :current-user="currentUser" />
      <group-chat-conversation-screen v-show="groupSelected" :current-user="currentUser" />

      <div v-if="!selectedFriend && !groupSelected " class="col-md-9">
        <div class="chat-panel" :class="{ 'chat-panel-dark': nightMode } ">
          <div style="height:80vh;color:white;padding-top:25%;padding-left:35%">
            Select a friend or a group to start chatting.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'form-backend-validation';
import ChatFriendList from './chatFriendList';
import ChatConversationScreen from './chatConversationScreen';
import GroupChatConversationScreen from './groupChatConversationScreen';
import FriendRequestModal from './modals/friendRequestsModal';
import ProfileSettingsModal from './modals/profileSettingsModal';
import AddFriendModal from './modals/addFriendModal';
import CreateGroupModal from './modals/CreateGroupModal';
import ChatGroupList from './chatGroupList';
import GroupInvitesModal from './modals/groupInvitesModal';

export default {
    name: 'ChatIndex',

    components: {
        ChatGroupList,
        ProfileSettingsModal,
        ChatConversationScreen,
        GroupChatConversationScreen,
        ChatFriendList,
        FriendRequestModal,
        AddFriendModal,
        CreateGroupModal,
        GroupInvitesModal
    },

    props: ['currentUser'],

    data() {
        return {
            form: new Form({
                    email: null
                },
            ),
            show: false,
            friendRequests: null,
            groupInvites: null,
            nightMode: false,
            selectedFriend: null,
            groupSelected: null,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            rand: 1,
            visibleSaveButton: false,
            search: '',
            friends: [],
            groups: []
        }
    },

    computed: {
        filteredItems() {
            if(this.visibleSaveButton){
                return this.groups
                    .filter(item => item.name.toLowerCase().includes(this.search.toLocaleLowerCase()))
                    .sort((a, b) => a['name'] > b['name'] ? 1 : -1);
            }

            return this.friends
                .filter(item => item.friend.name.toLowerCase().includes(this.search.toLocaleLowerCase()))
                .sort((a, b) => a['name'] > b['name'] ? 1 : -1);
        }
    },

    mounted() {
        window.Echo.private(`messages.${this.currentUser.id}`)
            .listen('messageSend', (e) => {
                this.$eventHub.$emit('messageReceived', e.message);
                console.log(e.message);
            })
            .listen('userBlocked', (e) => {
                this.$eventHub.$emit('userBlocked', e);
                console.log(e);
            });

        window.Echo.private(`messageEdited.${this.currentUser.id}`)
            .listen('messageEdited', (e) => {
                this.$eventHub.$emit('messageEdited', e.message);
                console.log(e.message);
            });

        window.Echo.private(`messageDeleted.${this.currentUser.id}`)
            .listen('messageDeleted', (e) => {
                this.$eventHub.$emit('messageDeleted', e.id);
                console.log(e.id);
            });

        window.Echo.private(`messageSeen.${this.currentUser.id}`)
            .listen('messageSeen', (e) => {
                console.log('messageSeen');
                this.$eventHub.$emit('messageSeen', e.messageIds);
                console.log(e.messageIds);
            });

        this.getFriends();
        this.getGroups();

        this.getFriendRequests();

        this.getGroupInvites();

        this.$eventHub.$on('friendClick', this.friendClick);
        this.$eventHub.$on('groupClick', this.groupClick);

        this.$eventHub.$on('profileImageUpdated', this.profileImageUpdated);

        this.$eventHub.$on('friendRequestSent', this.getFriendRequests);

        this.$eventHub.$on('friendRequestApproved',this.friendRequestApproved);
        this.$eventHub.$on('groupInviteApproved',this.groupInviteApproved);
        this.$eventHub.$on('groupMemberRemove',this.groupMemberRemove);

        this.nightMode = (localStorage.getItem('nightMode') === 'true')

    },
    methods: {

        async submit() {
            await this.form.post('/api/friend-request');

            this.$refs['add-person'].hide();

            await this.getFriendRequests();
        },

        async getFriends(){
            const response = await this.$http.get('/api/friend-list');
            this.friends = response.data.data;
        },

        friendRequestApproved(friend){
            this.friends.push(friend);
        },

        async getGroups(){
            const response = await this.$http.get('/api/get-groups')
            this.groups = response.data.data;

            await this.listenGroups();
        },

        listenGroups(){

            for (let i=0; i < this.groups.length; i++){

                 window.Echo.private(`groupMessages.${this.groups[i].id}`)
                .listen('groupMessageSend', (e) => {
                    this.$eventHub.$emit('groupMessageReceived', e.message);
                }).listen('groupMessageEdited', (e) => {
                     this.$eventHub.$emit('groupMessageEdited', e.message);
                }).listen('groupMessageDeleted', (e) => {
                     this.$eventHub.$emit('groupMessageDeleted', e.id,e.deleted_by);
                 });

                 console.log(`groupMessageSeen.${this.groups[i].id}.${this.currentUser.id}`);

                window.Echo.private(`groupMessageSeen.${this.groups[i].id}.${this.currentUser.id}`)
                .listen('groupMessageSeen', (e) => {
                    this.$eventHub.$emit('groupMessageSeen', e);
                    console.log(e);
                });

            }
        },

        groupInviteApproved(group){
            this.groups.push(group);
        },

        groupMemberRemove(groupMember){
            for( let i = 0; i < this.groups.length; i++){

                if ( groupMember.member.id === this.currentUser.id && this.groups[i].id === groupMember.group.id) {

                    this.groups.splice(i, 1);
                    i--;
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

        toggleDropDown() {

            this.show = !this.show;

            if (this.show) this.$refs['dropdown'].show();
            else this.$refs['dropdown'].hide()

        },

        showAddPersonModal() {
            this.$eventHub.$emit('showAddFriendModal');
        },

        showFriendRequestsModal() {
            //this.$eventHub.$emit('showFriendRequestsModal', this.friendRequest);
            this.$eventHub.$emit('showFriendRequestsModal');
        },

        showGroupInvitesModal() {
            this.$eventHub.$emit('showGroupInvitesModal', this.groupInvites);
        },

        showCreateGroupModal() {
            this.$eventHub.$emit('showCreateGroupModal');
        },

        showProfileSettingsModal() {
            this.$eventHub.$emit('showProfileSettingsModal');
        },

        async getFriendRequests() {
            const response = await this.$http.get('/api/get-friend-requests');
            this.friendRequest = response.data.data;
        },

        async getGroupInvites() {
            const response = await this.$http.get('/api/get-group-invites');
            this.groupInvites = response.data.data;
        },

        nightModeOn() {

            this.$eventHub.$emit('nightModeOn');

            if (this.nightMode === true) {
                this.nightMode = false;
                localStorage.setItem('nightMode', false);
            } else {
                this.nightMode = true;
                localStorage.setItem('nightMode', true);
            }
        },

        friendClick(item) {
            this.selectedFriend = item.friend;

            this.groupSelected = false;
        },

        groupClick() {
            this.selectedFriend = null;
            this.groupSelected = true;
        },

        profileImageUpdated(newImage) {
            this.currentUser.image = newImage;
            this.rand = Date.now();
        },

        handleFriendsTabClick() {
            this.visibleSaveButton = false;
        },

        handleGroupsTabClick() {
            this.visibleSaveButton = true;
        },

    }
}
</script>

<style scoped>
.nav-tabs .nav-link.active {
    background: #292929;
    color: #3490dc;
}
</style>
