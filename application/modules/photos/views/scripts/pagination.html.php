<?php if($this->pageCount) { ?>
<?php if($this->previous) { ?>
                                        <a href="<?= $this->baseUri . "/page/{$this->previous}" ?>" title="Previous Page">&laquo;</a>
<?php if($this->first < $this->firstPageInRange) { ?>
                                        <a href="<?= $this->baseUri . "/page/{$this->first}" ?>" title="Page <?= $this->first ?>"><?= $this->first ?></a> ...
<?php } /*if(first < firstPageInRange)*/ ?>
<?php } /*if(previous)*/ ?>
<?php foreach($this->pagesInRange as $page) { ?>
                                        <a href="<?= $this->baseUri . "/page/$page" ?>" title="Page <?= $page ?>" class="<?= $this->current == $page?"current":"" ?>"><?= $page ?></a>
<?php } /*foreach(pagesInRange as $page)*/ ?>
<?php if($this->next) { ?>
<?php if($this->last > $this->lastPageInRange) { ?>
                                        ... <a href="<?= $this->baseUri . "/page/{$this->last}" ?>" title="Page <?= $this->last ?>"><?= $this->last ?></a>
<?php } /*if(last > lastPageInRange)*/ ?>
                                        <a href="<?= $this->baseUri . "/page/{$this->next}" ?>" title="Next Page">&raquo;</a>
<?php } /*if(next)*/ ?>
<?php } /*if(pageCount)*/ ?>
