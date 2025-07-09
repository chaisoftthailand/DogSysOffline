<FORM NAME="EmployeeForm" METHOD="POST" ACTION="<?=$_SERVER["SCRIPT_NAME"]?>">
  <style>
    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 22px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0;
      right: 0; bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 22px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 16px; width: 16px;
      left: 3px; bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:checked + .slider:before {
      transform: translateX(18px);
    }

    .switch-label {
      color: #fff;
      font-size: 14px;
    }

    .switch-wrapper {
      display: flex;
      align-items: center;
      gap: 6px;
    }
  </style>

  <div class="switch-wrapper">    
    ดำ
    <label class="switch">
      <input type="checkbox" name="Mode" onchange="this.form.submit()" <?php if ($Mode == 'on') echo "checked"; ?>>
      <span class="slider"></span>
    </label>
    ขาว
  </div>
</FORM>
