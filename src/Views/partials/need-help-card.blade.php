@if(env('EASY_ADMIN_SUPPORT_EMAIL') != NULL)
    <div class="card mb-3 bg-light">
        <div class="card-header">
            <i class="fas fa-info-circle"></i>
            Need Help?
        </div>
          <div class="card-body pt-2">
              <small>
                  Contact us at: 
                  <a target="_blank" href="mailto:{{ env('EASY_ADMIN_SUPPORT_EMAIL') }}?subject=Easy Admin Help Request">{{ env('EASY_ADMIN_SUPPORT_EMAIL') }}</a>
              </small>
          </div>
    </div>
@endif