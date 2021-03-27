<template>
  <div v-if="friend" class="col-md-9">
    <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
      <div
        class="friend-drawer no-gutters"
        :class="{ 'friend-drawer--grey' : !nightMode, 'friend-drawer--dark' : nightMode }"
      >
        <img
          class="profile-image"
          src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg"
          alt=""
        >
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ friend.friend.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ friend.friend.about }}
          </p>
        </div>
        <span class="settings-tray--right" style="margin-left:220px">
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
      <div class="overflow-auto" style="height:600px;">
        <!--
    <div class="row no-gutters">
      <div class="col-md-3">
        <div class="chat-bubble chat-bubble&#45;&#45;left">
          <div class="chat-buble-name">
            Robo Cop
          </div> &lt;!&ndash; show only in groups &ndash;&gt;
          Hello dude!
          <span style="float:right">
            <a
              id="dropdownMenu2"
              role="button"
              data-toggle="dropdown"
              style=" cursor: pointer"
            >
              <i class="bi bi-chevron-down" />
              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                <button
                  class="dropdown-item edit-message"
                  type="button"
                  data-toggle="modal"
                  data-target="#editMessage"
                >Edit Message</button>

                <button
                  class="dropdown-item delete-message"
                  type="button"
                  data-toggle="modal"
                  data-target="#deleteMessage"
                >Delete
                  Message</button>
              </div>
            </a>
          </span>
          <div class="chat-buble-time">
            15:52
          </div>
        </div>
      </div>
    </div>
    <div class="row no-gutters">
      <div class="col-md-3 offset-md-9">
        <div class="chat-bubble chat-bubble&#45;&#45;right">
          <div class="chat-buble-name">
            Robo Cop
          </div> &lt;!&ndash; show only in groups &ndash;&gt;
          Hello dude!
          <span style="float:right">
            <a
              id="dropdownMenu3"
              role="button"
              data-toggle="dropdown"
              style=" cursor: pointer"
            >
              <i class="bi bi-chevron-down" />
              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                <button
                  class="dropdown-item edit-message"
                  type="button"
                  data-toggle="modal"
                  data-target="#editMessage"
                >Edit Message</button>

                <button
                  class="dropdown-item delete-message"
                  type="button"
                  data-toggle="modal"
                  data-target="#deleteMessage"
                >Delete
                  Message</button>
              </div>
            </a>
          </span>
          <div class="chat-buble-time">
            15:52
          </div>
        </div>
      </div>
    </div>-->

        <edit-message-modal />

        <message-list :key="messageListKey" :items="items" :current-user="currentUser" />

        <div v-for="item in items" :key="item.id" class="row no-gutters">
          <div
            class="col-md-3"
            :class="item.from === currentUser.id ?'offset-md-9':''"
          >
            <div
              class="chat-bubble"
              :class="item.from === currentUser.id ? 'chat-bubble--right': 'chat-bubble--left'"
            >
              {{ item.message }}

              <span v-if="item.from === currentUser.id" style="float:right">
                <a
                  id="dropdownMenu3"
                  role="button"
                  data-toggle="dropdown"
                  style=" cursor: pointer"
                >
                  <i class="bi bi-chevron-down" />
                  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                    <button
                      class="dropdown-item edit-message"
                      type="button"
                      @click="showEditMessageModal(item)"
                    >Edit Message</button>

                    <button
                      class="dropdown-item delete-message"
                      type="button"
                      @click="deleteMessage(item.id)"
                    >Delete
                      Message</button>
                  </div>
                </a>
              </span>
            </div>
          </div>
        </div>

      </div>
    </div>
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
  </div>

  <div v-else style="margin-top:20%;margin-left:25%" class="alert alert-info">
    Select a friend or a group to start chatting.
  </div>
</template>

<script>
import EditMessageModal from './modals/editMessageModal';
import MessageList from './messageList';

export default {
    name: 'ChatConversationScreen',
    components: {MessageList, EditMessageModal },
    props: ['currentUser'],

    data (){
        return {
            items : [],
            friend: null,
            nightMode: false,
            messageListKey: 0,
        }
    },

    mounted() {
        this.$eventHub.$on('friendClick',this.fetch);
        this.$eventHub.$on('nightModeOn',this.nightModeOn);
        this.$eventHub.$on('messageEdited',this.fetch);
        this.nightMode = (localStorage.getItem('nightMode') === 'true')
    },

    methods: {
        async fetch(item){
            const response = await this.$http.get('/api/message/'+item.roomId);
            this.items = response.data.data;
            this.friend = item;
        },

        nightModeOn() {

            if (this.nightMode === true) {
                this.nightMode = false;
                localStorage.setItem('nightMode', false);
            } else {
                this.nightMode = true;
                localStorage.setItem('nightMode', true);
            }
        },

        forceRerender() {
            this.messageListKey += 1;
        }
    }

}
</script>
