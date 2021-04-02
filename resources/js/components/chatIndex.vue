<template>
  <div>
    <add-friend-modal :current-user="currentUser" />
    <friend-request-modal :current-user="currentUser" />
    <profile-settings-modal :current-user="currentUser" />

    <div class="row no-gutters">
      <div class="col-md-3 border-right" :class="{'friend-dark': nightMode}">
        <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
          <img
            class="profile-image"
            :src="'images/'+currentUser.image"
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
              <b-dropdown-item @click="showProfileSettingsModal">
                <b-icon icon="gear-fill" aria-hidden="true" />
                Profile Settings
              </b-dropdown-item>
              <b-dropdown-item @click="nightModeOn">
                <b-icon icon="moon" aria-hidden="true" />
                Night Mode
              </b-dropdown-item>
              <b-dropdown-item href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
            <input placeholder="Search here" type="text">
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
            >Groups</a>
          </li>
        </ul>

        <div id="myTabContent" class="tab-content">
          <div id="friend" class="tab-pane fade show active" role="tabpanel" aria-labelledby="friend-tab">
            <chat-friend-list :night-mode="nightMode" />
          </div>

          <div id="group" class="tab-pane fade" role="tabpanel" aria-labelledby="group-tab">
            <div class="messages overflow-auto" style="height: 700px;">
              <div class="friend-drawer friend-drawer--onhover" :class="{'friend-dark' : nightMode}">
                <img
                  class="profile-image"
                  src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg"
                  alt=""
                >
                <div class="text" :class=" {'text-white' : nightMode}">
                  <h6>Family Group</h6>
                  <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}" />
                </div>
                <span class="time text-muted small">13:21</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--      <div class="col-md-9">
        <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
          <div
            class="friend-drawer no-gutters"
            :class="{ 'friend-drawer&#45;&#45;grey' : !nightMode, 'friend-drawer&#45;&#45;dark' : nightMode }"
          >
            <img
              class="profile-image"
              src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg"
              alt=""
            >
            <div class="text" :class=" {'text-white' : nightMode}">
              <h6>Robo Cop</h6>
              <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
                Layin' down the law since like before Christ...
              </p>
            </div>
            <span class="settings-tray&#45;&#45;right" style="margin-left:220px">
              <a id="dropdownMenu2" role="button" data-toggle="dropdown">
                <i class="material-icons">menu</i>

                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <button
                    class="dropdown-item group-member-list"
                    type="button"
                    data-toggle="modal"
                    data-target="#groupMemberList"
                  >Group Member List</button>
                </div>
              </a>
            </span>
          </div>
        </div>

        <div class="chat-panel" :class="{ 'chat-panel-dark': nightMode } ">
          <div class="overflow-auto" style="height:600px;">-->
      <chat-conversation-screen :current-user="currentUser" />
      <!--          </div>

          <div class="row">
            <div class="col-12">
              <div class="">
                <div class="chat-box-tray" :class="{ 'chat-box-tray-dark': nightMode}">
                  <i class="material-icons">sentiment_very_satisfied</i>
                  <input type="text" placeholder="Type your message here...">
                  <i class="material-icons">mic</i>
                  <i class="material-icons">send</i>
                </div>
              </div>
            </div>
          </div>

        </div>-->
    </div>
  </div>
  </div>
</template>

<script>
import Form from 'form-backend-validation';
import ChatFriendList from './chatFriendList';
import ChatConversationScreen from './chatConversationScreen';
import FriendRequestModal from './modals/friendRequestsModal';
import ProfileSettingsModal from './modals/profileSettingsModal';
import AddFriendModal from './modals/addFriendModal';

export default {
    name: 'ChatIndex',
    components: { ProfileSettingsModal, ChatConversationScreen, ChatFriendList, FriendRequestModal, AddFriendModal },
    props: ['currentUser'],

    data() {
        return {
            form: new Form({
                    email: null
                },
            ),
            show: false,
            friendRequests: null,
            nightMode: false,
            selectedFriend: null,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },

    mounted() {
            window.Echo.private(`messages.${this.currentUser.id}`)
                .listen('messageSend', (e) => {
                    this.$eventHub.$emit('messageReceived', e.message);
                    console.log(e.message);
                });

        this.getFriendRequests();

        this.$eventHub.$on('refreshFriendRequests', this.getFriendRequests);

        this.$eventHub.$on('friendClick', this.friendClick);

        this.nightMode = (localStorage.getItem('nightMode') === 'true')

    },
    methods: {

        async submit() {
            await this.form.post('/api/friend-request');

            this.$refs['add-person'].hide();

            this.getFriendRequests();
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
            this.$eventHub.$emit('showFriendRequestsModal', this.friendRequest);
        },

        showProfileSettingsModal() {
            this.$eventHub.$emit('showProfileSettingsModal');
        },

        async getFriendRequests() {
            const response = await this.$http.get('/api/get-friend-requests');
            this.friendRequest = response.data.data;
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

        friendClick(item){
            this.selectedFriend = item.friend;
        }

    }
}
</script>

<style scoped>
.nav-tabs .nav-link.active{
    background: #292929;
    color: #3490dc;
}
</style>
