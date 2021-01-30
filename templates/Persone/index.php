<b-form inline action="" method="GET">
  <b-container>
    <b-row>
      <b-col cols="3">
        <b-form-input placeholder="Cerca" name="q" v-model="q" type="search"></b-form-input>
      </b-col>
      <b-col cols="5">

        <v-select placeholder="Tags" :options="tagList" name="tags" v-model="tagArray" multiple label="name" @search="fetchTags" style="width:100%">
        </v-select>

      </b-col>
      <b-col cols="3">
        <b-form-input name="nazione" v-model="nazione" placeholder="Nazione" />
      </b-col>
      <b-col cols="1">
        <b-button type="submit" @click.prevent="search()">Filtra</b-button>
      </b-col>
    </b-row>

    <b-row cols="12" class="float-right mt-2">
      <b-col>
        <b-link class="btn btn-primary btn-sm" :href="`/campaigns/edit${qString}`" title="Invia mail alla lista" v-b-tooltip.hover size="sm">
          <b-icon-envelope-fill></b-icon-envelope-fill>
          Invia Mail
        </b-link>
        <b-link class="btn btn-primary btn-sm" :href="`/persone/add-tags${qString}`" title="Aggiungi tag ai selezionati" v-b-tooltip.hover size="sm">
          <b-icon-tags>
          </b-icon-tags> Aggiungi Tag
        </b-link>
        <b-link class="btn btn-primary btn-sm" href="/persone/add" title="Aggiungi una persona" v-b-tooltip.hover size="sm">
          <b-icon-plus-circle>
          </b-icon-plus-circle> Aggiungi Contatto
        </b-link>
        <b-link class="btn btn-primary btn-sm" href="/persone/delete" title="Elimina righe selezionate" v-b-tooltip.hover size="sm">
          <b-icon-trash>
          </b-icon-trash> Elimina
        </b-link>
      </b-col>
    </b-row>
  </b-container>
  <b-table :items="fetchRows" :fields="colonne" class="mt-2" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :busy.sync="isBusy" id="contacts" striped>
    <!-- A custom formatted header cell for field 'id' -->
    <template #head(id)="data">
      <b-checkbox @change="selectAll()"></b-checkbox>
    </template>


    <template v-slot:cell(id)="row">
      <b-checkbox name="id[]" :value="row.item.selected"></b-checkbox>
    </template>

    <template v-slot:cell(modified)="row">
      {{niceDate(row.item.modified)}}
    </template>

    <template v-slot:cell(DisplayName)="row">
      {{row.item.DisplayName}}
      <b-badge variant="primary" v-for="b in row.item.tag_list.split(',')" class="mr-1">{{b}}</b-badge><br>
      <small class="text-muted">{{row.item.Nome}} {{row.item.Cognome}} {{row.item.Societa}}</small>
    </template>

    <template v-slot:cell(azioni)="row">
      <a class="action" :href="`/persone/edit/${row.item.id}`">
        <b-icon-pencil></b-icon-pencil>
      </a>
      <b-link class="action" @click.prevent="delPersone(row.item.id)">
        <b-icon-trash></b-icon-trash>
      </b-link>
    </template>
  </b-table>
</b-form>


<ul class="pagination justify-content-center pagination-md">
  <li class="page-item">
    <a class="page-link" @click.prevent="changePage(pagination.page - 1)" :disabled="pagination.page <= 1">«</a>
  </li>
  <li v-for="page in pages" class="page-item" :class="isCurrentPage(page) ? 'active' : ''">
    <a class="page-link" :class="isCurrentPage(page) ? 'active' : ''" @click.prevent="changePage(page)">{{ page }}</a>
  </li>
  <li class="page-item">
    <a class="page-link" @click.prevent="changePage(pagination.page + 1)" :disabled="pagination.page >= pagination.pageCount">»</a>
  </li>
</ul>

<?= $this->Html->script('node_modules/axios/dist/axios.min.js', ['block' => 'pre-vue']) ?>
<?= $this->Html->script('node_modules/luxon/build/global/luxon.min', ['block' => 'pre-vue']) ?>
<?= $this->Html->script('node_modules/vue-router/dist/vue-router.min.js', ['block' => 'pre-vue']) ?>
<?= $this->Html->script('/js/node_modules/vue-select/dist/vue-select', ['block' => 'pre-vue']) ?>
<?= $this->Html->css('/js/node_modules/vue-select/dist/vue-select', ['block' => true])  ?>