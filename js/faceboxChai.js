
$(function(){
 
$("#delItem").facebox({
		title:'กรุณายืนยัน', // หัวข้อ dialog
		message:'<div style="width:250px;">กรุณายืนยันการลบรายการนี้? </div>', // 
		submitValue: "Yes", // ข้อความในปุ่มตกลง หรือยืนยัน
		submitFunction:function(){
			alert("Yes."); // คำสั่งหรือฟังก์ชัน เมื่อกดปุ่ม ตกลง กำหนดตามต้องการ
			$(document).trigger('close.facebox');	// ปิด facebox
		},
		submitFocus:true, // เมื่อเปิด facebox มาให้ focus ไปที่ปุ่มตกลง เลยหรือไม่
		cancelValue:"No", // ข้อความในปุ่มยกเลิก
		cancelFunction:function(){
			alert("No."); // คำสั่งหรือฟังก์ชัน เมื่อกดปุ่ม ปุ่มยกเลิก กำหนดตามต้องการ
			$(document).trigger('close.facebox');	// ปิด facebox
		}		
});
	
	
	
$("#show_info").facebox({
		title:'แสดงข้อความที่ซ่อน', // หัวข้อ dialog
		width:'200',  // ความกว้าง กำหนด หรือไม่ก็ได้
		cancelValue:"Close" // ข้อความในปุ่มยกเลิก
});	
 
$(".show_img").facebox({
		cancelValue:"Close" // ข้อความในปุ่มยกเลิก	
});	
 
$(".use_ajax").facebox({
		title:'การแสดงข้อมูลหัวข้อและรายละเอียดคำถาม', // หัวข้อ dialog
		width:'800',
		submitValue:"Send", // ข้อความในปุ่มตกลง หรือยืนยัน
		submitFunction:function(){
			$("#contact_form").submit();
			$(document).trigger('close.facebox');	// ปิด facebox
		},
		cancelValue:"Cancel" // ข้อความในปุ่มยกเลิก		
});	
 
 
$(".use_ajax_notitle").facebox({
		width:'400', // ความกว้าง กำหนด หรือไม่ก็ได้
		submitValue:"Send", // ข้อความในปุ่มตกลง หรือยืนยัน
		submitFunction:function(){
			$("#contact_form").submit();
			$(document).trigger('close.facebox');	// ปิด facebox
		},
		submitFocus:true, // เมื่อเปิด facebox มาให้ focus ไปที่ปุ่มตกลง เลยหรือไม่
		cancelValue:"Cancel" // ข้อความในปุ่มยกเลิก		
});	
	
 
});